<?php

namespace WebChemistry\Invoice;

use Intervention\Image\AbstractShape;
use Intervention\Image\Gd\Font;
use Intervention\Image\Image;
use Intervention\Image\ImageManagerStatic;
use Nette\Localization\ITranslator;
use Nette\Object;
use Nette\Utils\Callback;
use WebChemistry\Invoice\Components\Paginator;
use WebChemistry\Invoice\Data\Company;
use WebChemistry\Invoice\Data\Customer;
use WebChemistry\Invoice\Data\Item;
use WebChemistry\Invoice\Data\Payment;
use WebChemistry\Invoice\Data\Template;
use Nette\Utils\Strings;

class Invoice extends Object {

	/** @var Company */
	protected $company;

	/** @var Template */
	private $template;
	
	/** @var Payment */
	private $payment;
	
	/** @var Customer */
	private $customer;
	
	/** @var Image */
	private $image;
	
	/** @var Translator */
	private $translator;

	/** @var Paginator */
	private $paginator;

	/** @var callable */
	private $formatNumber;

	/** @var callable */
	private $save;

	/**
	 * @param Company $company
	 * @param Template $template
	 */
	public function __construct(Company $company, Template $template = NULL) {
		$this->company = $company;
		$this->template = $template ? : new Template();
		$this->translator = new Translator();
	}

	/**
	 * @param callable $callback
	 * @return self
	 */
	public function setFormatNumber($callback) {
		$this->formatNumber = Callback::check($callback);

		return $this;
	}

	/**
	 * @param float $float
	 * @param int $decimals
	 * @return string
	 */
	private function formatNumber($float, $decimals = 2) {
		$call = $this->formatNumber;

		return $call ? $call($float, $decimals) : number_format($float, $decimals);
	}

	/**
	 * @param ITranslator $translator
	 * @return self
	 */
	public function setTranslator(ITranslator $translator) {
		$this->translator = $translator;

		return $this;
	}
	
	/**
	 * @param Template $template
	 * @return self
	 */
	public function setTemplate(Template $template) {
		$this->template = $template;

		return $this;
	}

	/**
	 * @param callable $callback
	 * @return self
	 */
	public function setSave($callback) {
		$this->save = Callback::check($callback);

		return $this;
	}

	/**
	 * @param string $message
	 * @return string
	 */
	protected function translate($message) {
		return $this->translator->translate($message);
	}

	/**
	 * @return Translator
	 */
	public function getTranslator() {
		return $this->translator;
	}

	/************************* Rendering **************************/

	/**
	 * @param Customer $customer
	 * @param Payment $payment
	 * @throws Exception
	 * @return Image[]
	 */
	public function create(Customer $customer, Payment $payment) {
		$this->customer = $customer;
		$this->payment = $payment;
		$customer->check();
		$payment->check();
		$this->company->check();
		$this->paginator = $paginator = $this->template->getPaginator();
		$paginator->setItems($payment->getItems());
		$return = [];
		$save = $this->save;

		for ($page = 1; $paginator->getTotalPages() >= $page; $page++) {
			$paginator->setCurrentPage($page);

			$this->initialize();

			$this->rightHeader();
			$this->footer();
			$this->paymentInformation();
			$this->customer();

			// Max. 9 items
			$i = 0;
			foreach ($paginator->getItems() as $i => $item) {
				$this->item($i, $item);
			}

			$plus = 1271 + ($i + 1) * 152;

			// Last page price
			if ($paginator->isLastPage()) {
				if ($this->company->isTax()) {
					$this->tax($plus);
				}

				if ($paginator->isLastPage()) {
					$this->totalPrice($plus);
				}
			}

			if ($save) {
				$save($page);
			} else {
				$return[] = $this->image;
			}
		}
		$this->image = NULL;

		return $return;
	}

	protected function customer() {
		$text = Strings::upper($this->translate('subscriber'));

		$this->image->text($text, 135, 700, function (Font $font) {
			$font->size(65);
			$font->color($this->template->getPrimaryColor());
			$font->file($this->template->getFontBold());
		});

		list($left,, $right) = imageftbbox(65, 0, $this->template->getFontBold(), $text);
		$x = $right - $left + 185;

		$name = $this->customer->getName();
		list($left,, $right) = imageftbbox(30, 0, $this->template->getFontBold(), $name);
		$secX = $right - $left + 185;

		if ($secX > $x) {
			$x = $secX;
		}

		$this->image->polygon([
			$x, 720,
			$x, 760,
			$x - 30, 730,
			0, 730,
			0, 720
		], function (AbstractShape $shape) {
			$shape->background($this->template->getPrimaryColor());
		});

		$this->image->text($name, 135, 800, function (Font $font) {
			$font->size(40);
			$font->color($this->template->getFontColor());
			$font->file($this->template->getFontBold());
		});

		$multiplier = 0;

		$this->image->text($this->customer->getZip() . ', ' . $this->customer->getTown(), 135, 850 + ($multiplier * 35), function (Font $font) {
			$font->size(27);
			$font->file($this->template->getFont());
			$font->color($this->template->getFontColor());
		});
		$multiplier++;

		$this->image->text($this->customer->getAddress(), 135, 850 + ($multiplier * 35), function (Font $font) {
			$font->size(27);
			$font->file($this->template->getFont());
			$font->color($this->template->getFontColor());
		});
		$multiplier++;

		$this->image->text($this->customer->getCountry(), 135, 850 + ($multiplier * 35), function (Font $font) {
			$font->size(27);
			$font->file($this->template->getFont());
			$font->color($this->template->getFontColor());
		});
		$multiplier++;

		if ($this->customer->getTin()) {
			$this->image->text(Strings::upper($this->translate('vat')) . ': ' . $this->customer->getTin(), 135, 850 + ($multiplier * 35), function (Font $font) {
				$font->size(27);
				$font->file($this->template->getFont());
				$font->color($this->template->getFontColor());
			});
			$multiplier++;
		}

		if ($this->customer->getVaTin()) {
			$this->image->text(Strings::upper($this->translate('vaTin')) . ': ' . $this->customer->getVaTin(), 135, 850 + ($multiplier * 35), function (Font $font) {
				$font->size(27);
				$font->file($this->template->getFont());
				$font->color($this->template->getFontColor());
			});
		}
	}

	protected function rightHeader() {
		$this->image->text($this->translate('date') . ': ' . $this->payment->getDate()->format('d/m/Y'), 1700, 180, function (Font $font) {
			$font->file($this->template->getFont());
			$font->size(25);
			$font->color($this->template->getColorOdd());
			$font->align('right');
		});

		$this->image->text(Strings::upper($this->translate('invoice')), 1750, 190, function (Font $font) {
			$font->valign('center');
			$font->align('left');
			$font->color($this->template->getColorOdd());
			$font->size(75);
			$font->file($this->template->getFontBold());
		});

		$this->image->text($this->translate('invoiceNumber') . ': ' . $this->payment->getInvoiceNumber(), 1700, 230, function (Font $font) {
			$font->file($this->template->getFont());
			$font->size(25);
			$font->color($this->template->getColorOdd());
			$font->align('right');
		});
	}

	/**
	 * @return Image
	 */
	protected function initialize() {
		$this->image = ImageManagerStatic::canvas(2408, 3508, '#fff');

		$this->image->polygon([
			0, 0,
			0, 400,
			980, 400,
			1250, 0
		], function (AbstractShape $shape) {
			$shape->background($this->template->getFontColor());
		});

		// Draw header
		$this->image->polygon([
			1250, 0,
			980, 400,
			$this->image->getWidth(), 400,
			$this->image->getWidth(), 0
		], function (AbstractShape $shape) {
			$shape->background($this->template->getPrimaryColor());
		});
		$this->image->polygon([
			0, 400,
			0, 425,
			675, 425,
			755, 480,
			800, 400
		], function (AbstractShape $shape) {
			$shape->background($this->template->getPrimaryColor());
		});

		$color = $this->template->getPrimaryColor();
		$color[0] = $color[0] + 90;

		if ($color[0] > 255) {
			$color[0] = 255;
		}

		$this->image->polygon([
			800, 400,
			755, 480,
			820, 500,
			890, 400
		], function (AbstractShape $shape) use ($color) {
			$shape->background($color);
		});

		$this->image->polygon([
			890, 400,
			870, 425,
			970, 425,
			990, 400
		], function (AbstractShape $shape) {
			$shape->background($this->template->getPrimaryColor());
		});

		// Company address (Left header)
		if ($this->company->getLogo()) {
			$size = @getimagesize($this->company->getLogo());

			if ($size) {
				$this->image->insert($this->company->getLogo(), 'top-left', (int) ((980 - $size[0]) / 2), 50);
			}
		}

		$y = $this->company->getLogo() ? 220 : 120;

		$this->image->text($this->company->getZip() . ' ' . $this->company->getTown(), 450, $y, function (Font $font) {
			$font->file($this->template->getFont());
			$font->color($this->template->getColorOdd());
			$font->align('right');
			$font->size(27);
		});
		$this->image->text($this->company->getAddress(), 450, $y + 50, function (Font $font) {
			$font->file($this->template->getFont());
			$font->color($this->template->getColorOdd());
			$font->align('right');
			$font->size(27);
		});
		$this->image->text($this->company->getCountry(), 450, $y + 100, function (Font $font) {
			$font->file($this->template->getFont());
			$font->color($this->template->getColorOdd());
			$font->align('right');
			$font->size(27);
		});

		$multiplier = 0;
		if ($this->company->getTin()) {
			$this->image->text(Strings::upper($this->translate('vat')) . ': ' . $this->company->getTin(), 520, $y + ($multiplier * 50), function (Font $font) {
				$font->file($this->template->getFont());
				$font->color($this->template->getColorOdd());
				$font->align('left');
				$font->size(27);
			});
			$multiplier++;
		}

		if ($this->company->getVaTin()) {
			$this->image->text(Strings::upper($this->translate('vaTin')) . ': ' . $this->company->getVaTin(), 520, $y + ($multiplier * 50), function (Font $font) {
				$font->file($this->template->getFont());
				$font->color($this->template->getColorOdd());
				$font->align('left');
				$font->size(27);
			});
			$multiplier++;
		}

		$this->image->text($this->company->isTax() ? $this->translate('taxPay') : $this->translate('notTax'),
			520, $y + ($multiplier * 50), function (Font $font) {
			$font->file($this->template->getFont());
			$font->color($this->template->getColorOdd());
			$font->align('left');
			$font->size(27);
		});

		// Company name or full name
		$this->image->text($this->company->getName(), 1775, 100, function (Font $font) {
			$font->file($this->template->getFont());
			$font->color($this->template->getColorOdd());
			$font->align('center');
			$font->size(35);
		});

		// Payment informations
		$this->image->text(Strings::upper($this->translate('paymentData')), 1500, 500, function (Font $font) {
			$font->file($this->template->getFont());
			$font->color($this->template->getPrimaryColor());
			$font->align('left');
			$font->valign('top');
			$font->size(65);
		});
		$this->image->polygon([
			1450, 580,
			1450, 620,
			1480, 590,
			$this->image->getWidth(), 590,
			$this->image->getWidth(), 580
		], function (AbstractShape $shape) {
			$shape->background($this->template->getPrimaryColor());
		});

		$this->itemsHeader();

		$this->image->rectangle(0, $this->image->getHeight() - 80, $this->image->getWidth(), $this->image->getHeight(), function (AbstractShape $shape) {
			$shape->background($this->template->getFontColor());
		});

		$this->image->text($this->company->getFooter(), $this->image->getWidth() / 2, $this->image->getHeight() - 40, function (Font $font) {
			$font->file($this->template->getFont());
			$font->color($this->template->getColorOdd());
			$font->align('center');
			$font->size(24);
			$font->valign('center');
		});
	}

	protected function footer() {
		$this->image->text($this->translate('page') . ' ' . $this->paginator->getCurrentPage() . ' ' . $this->translate('from') . ' ' . $this->paginator->getTotalPages(),
			$this->image->getWidth() - 40, $this->image->getHeight() - 40, function (Font $font) {
				$font->file($this->template->getFont());
				$font->valign('center');
				$font->align('right');
				$font->color($this->template->getColorOdd());
				$font->size(24);
			});
	}

	/**
	 * @param $plus
	 * @return mixed
	 */
	protected function totalPrice($plus) {
		$this->image->rectangle(1550, $plus, $this->image->getWidth(), $plus + 100, function (AbstractShape $shape) {
			$shape->background($this->template->getColorEven());
		});

		$this->image->polygon([
			$this->image->getWidth(), $plus,
			$this->image->getWidth(), $plus + 100,
			2000, $plus + 100,
			2150, $plus
		], function (AbstractShape $shape) {
			$shape->background($this->template->getPrimaryColor());
		});

		$this->image->text($this->translate('totalPrice') . ':', 1800, $plus + 65, function (Font $font) {
			$font->align('center');
			$font->color($this->template->getFontColor());
			$font->file($this->template->getFontBold());
			$font->size(37);
		});

		$this->image->text($this->formatNumber($this->getTotalPrice(TRUE)) . ' ' . $this->payment->getCurrency(), 2260, $plus + 65, function (Font $font) {
			$font->align('center');
			$font->color($this->template->getColorOdd());
			$font->file($this->template->getFontBold());
			$font->size(27);
		});

		return $plus + 100;
	}

	/**
	 * @return int
	 */
	private function getTotalPrice($useTax = FALSE) {
		$total = 0;
		$tax = $useTax && $this->company->isTax() ? $this->payment->getTax() + 1 : 1;

		foreach ($this->payment->getItems() as $item) {
			$total += $item->getPrice() * $item->getCount();
		}

		return $total * $tax;
	}

	/**
	 * @param $plus
	 */
	protected function tax(&$plus) {
		$this->image->rectangle(1550, $plus, $this->image->getWidth(), $plus + 100, function (AbstractShape $shape) {
			$shape->background($this->template->getColorEven());
		});

		$this->image->text($this->translate('subtotal') . ':', 1800, $plus + 65, function (Font $font) {
			$font->align('center');
			$font->color($this->template->getFontColor());
			$font->file($this->template->getFontBold());
			$font->size(37);
		});

		$this->image->text($this->formatNumber($this->getTotalPrice(FALSE)) . ' ' . $this->payment->getCurrency(), 2260, $plus + 65, function (Font $font) {
			$font->align('center');
			$font->color($this->template->getFontColor());
			$font->file($this->template->getFont());
			$font->size(27);
		});

		$plus += 100;

		$this->image->rectangle(1550, $plus, $this->image->getWidth(), $plus + 100, function (AbstractShape $shape) {
			$shape->background($this->template->getColorEven());
		});

		$this->image->text(Strings::upper($this->translate('tax')) . ':', 1800, $plus + 65, function (Font $font) {
			$font->align('center');
			$font->color($this->template->getFontColor());
			$font->file($this->template->getFontBold());
			$font->size(37);
		});

		$this->image->text($this->payment->getTax() * 100 . ' %', 2260, $plus + 65, function (Font $font) {
			$font->align('center');
			$font->color($this->template->getFontColor());
			$font->file($this->template->getFont());
			$font->size(27);
		});

		$plus += 100;
	}

	private function insertInString($haystack, $pos, $string) {
		return substr($haystack, 0, $pos) . $string . substr($haystack, $pos);
	}

	/**
	 * @param $multiplier
	 * @param Item $item
	 */
	protected function item($multiplier, Item $item) {
		$plus = $multiplier * 152;

		$this->image->rectangle(0, 1270 + $plus, $this->image->getWidth(), 1270 + $plus + 150, function (AbstractShape $shape) use ($multiplier) {
			$shape->background($multiplier % 2 == 0 ? $this->template->getColorOdd() : $this->template->getColorEven());
		});
		$this->image->rectangle(0, 1420 + $plus, $this->image->getWidth(), 1420 + $plus + 2, function (AbstractShape $shape) {
			$shape->background($this->template->getPrimaryColor());
		});

		$text = Strings::truncate($item->getName(), 270);

		if (strlen($text) >= 69 && strlen($text) !== 69) {
			for ($i = 1; $i <= ceil(strlen($text) / 69); $i++) {
				$text = $this->insertInString($text, $i * 69, "\n");
			}
		}

		$this->image->text($text, 115, 1350 + $plus, function (Font $font) {
			$font->size(27);
			$font->file($this->template->getFont());
			$font->valign('center');
			$font->color($this->template->getFontColor());
		});

		$this->image->text($this->formatNumber($item->getCount(), 0), 1255, 1350 + $plus, function (Font $font) {
			$font->size(27);
			$font->file($this->template->getFont());
			$font->valign('center');
			$font->align('center');
			$font->color($this->template->getFontColor());
		});

		$this->image->text($this->formatNumber($item->getPrice()) . ' ' . $this->payment->getCurrency(), 1650, 1350 + $plus, function (Font $font) {
			$font->size(27);
			$font->file($this->template->getFont());
			$font->valign('center');
			$font->align('center');
			$font->color($this->template->getFontColor());
		});

		$this->image->text($this->formatNumber($item->getCount() * $item->getPrice()) . ' ' . $this->payment->getCurrency(), 2322, 1350 + $plus, function (Font $font) {
			$font->size(27);
			$font->file($this->template->getFontBold());
			$font->valign('center');
			$font->align('right');
			$font->color($this->template->getFontColor());
		});
	}

	protected function itemsHeader() {
		$this->image->rectangle(0, 1150, $this->image->getWidth(), 1265, function (AbstractShape $shape) {
			$shape->background($this->template->getColorEven());
		});
		$this->image->polygon([
			$this->image->getWidth(), 1150,
			$this->image->getWidth(), 1265,
			2000, 1265,
			2150, 1150
		], function (AbstractShape $shape) {
			$shape->background($this->template->getPrimaryColor());
		});
		$this->image->rectangle(0, 1265, $this->image->getWidth(), 1270, function (AbstractShape $shape) {
			$shape->background($this->template->getPrimaryColor());
		});

		$this->image->text(Strings::upper($this->translate('item')), 165, 1205, function (Font $font) {
			$font->color($this->template->getPrimaryColor());
			$font->file($this->template->getFontBold());
			$font->valign('center');
			$font->align('center');
			$font->size(37);
		});

		$this->image->text(Strings::upper($this->translate('count')), 1250, 1205, function (Font $font) {
			$font->color($this->template->getPrimaryColor());
			$font->file($this->template->getFontBold());
			$font->valign('center');
			$font->align('center');
			$font->size(37);
		});

		$this->image->text(Strings::upper($this->translate('pricePerItem')), 1650, 1205, function (Font $font) {
			$font->color($this->template->getPrimaryColor());
			$font->file($this->template->getFontBold());
			$font->valign('center');
			$font->align('center');
			$font->size(37);
		});

		$this->image->text(Strings::upper($this->translate('total')), 2250, 1205, function (Font $font) {
			$font->color($this->template->getColorOdd());
			$font->file($this->template->getFontBold());
			$font->valign('center');
			$font->align('center');
			$font->size(37);
		});
	}

	protected function paymentInformation() {
		$multiplier = 0;

		if ($this->payment->getMaturityDate()) {
			$this->image->text('&#xe660;', 1445, 710 + ($multiplier * 55), function (Font $font) {
				$font->color($this->template->getPrimaryColor());
				$font->file($this->template->getIconFont());
				$font->size(37);
			});
			$this->image->text($this->translate('dueDate') . ':', 1520, 705 + ($multiplier * 55), function (Font $font) {
				$font->color($this->template->getPrimaryColor());
				$font->size(27);
				$font->file($this->template->getFont());
			});
			$this->image->text($this->payment->getMaturityDate()->format('d/m/Y'), 1850, 705 + ($multiplier * 55), function (Font $font) {
				$font->size(27);
				$font->file($this->template->getFont());
				$font->color($this->template->getFontColor());
			});
			$multiplier++;
		}

		if ($this->payment->getAccountNumber()) {
			$this->image->text('&#xe645;', 1445, 710 + ($multiplier * 55), function (Font $font) {
				$font->color($this->template->getPrimaryColor());
				$font->file($this->template->getIconFont());
				$font->size(37);
			});
			$this->image->text($this->translate('accountNumber') . ':', 1520, 705 + ($multiplier * 55), function (Font $font) {
				$font->color($this->template->getPrimaryColor());
				$font->size(27);
				$font->file($this->template->getFont());
			});
			$this->image->text($this->payment->getAccountNumber(), 1850, 705 + ($multiplier * 55), function (Font $font) {
				$font->size(27);
				$font->file($this->template->getFont());
				$font->color($this->template->getFontColor());
			});
			$multiplier++;
		}

		if ($this->payment->getIBan()) {
			$this->image->text('&#xe645;', 1445, 710 + ($multiplier * 55), function (Font $font) {
				$font->color($this->template->getPrimaryColor());
				$font->file($this->template->getIconFont());
				$font->size(37);
			});
			$this->image->text(Strings::upper($this->translate('iban')) . ':', 1520, 705 + ($multiplier * 55), function (Font $font) {
				$font->color($this->template->getPrimaryColor());
				$font->size(27);
				$font->file($this->template->getFont());
			});
			$this->image->text($this->payment->getIBan(), 1850, 705 + ($multiplier * 55), function (Font $font) {
				$font->size(27);
				$font->file($this->template->getFont());
				$font->color($this->template->getFontColor());
			});
			$multiplier++;
		}

		if ($this->payment->getSwift()) {
			$this->image->text('&#xe645;', 1445, 710 + ($multiplier * 55), function (Font $font) {
				$font->color($this->template->getPrimaryColor());
				$font->file($this->template->getIconFont());
				$font->size(37);
			});
			$this->image->text(Strings::upper($this->translate('swift')) . ':', 1520, 705 + ($multiplier * 55), function (Font $font) {
				$font->color($this->template->getPrimaryColor());
				$font->size(27);
				$font->file($this->template->getFont());
			});
			$this->image->text($this->payment->getSwift(), 1850, 705 + ($multiplier * 55), function (Font $font) {
				$font->size(27);
				$font->file($this->template->getFont());
				$font->color($this->template->getFontColor());
			});
			$multiplier++;
		}

		if ($this->payment->getVariableSymbol()) {
			$this->image->text('&#xe6a3;', 1455, 710 + ($multiplier * 55), function (Font $font) {
				$font->color($this->template->getPrimaryColor());
				$font->file($this->template->getIconFont());
				$font->size(37);
			});
			$this->image->text($this->translate('varSymbol') . ':', 1520, 705 + ($multiplier * 55), function (Font $font) {
				$font->color($this->template->getPrimaryColor());
				$font->size(27);
				$font->file($this->template->getFont());
			});
			$this->image->text($this->payment->getVariableSymbol(), 1850, 705 + ($multiplier * 55), function (Font $font) {
				$font->size(27);
				$font->file($this->template->getFont());
				$font->color($this->template->getFontColor());
			});
			$multiplier++;
		}

		if ($this->payment->getConstantSymbol()) {
			$this->image->text('&#xe6a3;', 1455, 710 + ($multiplier * 55), function (Font $font) {
				$font->color($this->template->getPrimaryColor());
				$font->file($this->template->getIconFont());
				$font->size(37);
			});
			$this->image->text($this->translate('constSymbol') . ':', 1520, 705 + ($multiplier * 55), function (Font $font) {
				$font->color($this->template->getPrimaryColor());
				$font->size(27);
				$font->file($this->template->getFont());
			});
			$this->image->text($this->payment->getConstantSymbol(), 1850, 705 + ($multiplier * 55), function (Font $font) {
				$font->size(27);
				$font->file($this->template->getFont());
				$font->color($this->template->getFontColor());
			});
			$multiplier++;
		}

		$this->image->text('&#xe68c;', 1445, 710 + ($multiplier * 55), function (Font $font) {
			$font->color($this->template->getPrimaryColor());
			$font->file($this->template->getIconFont());
			$font->size(37);
		});
		$this->image->text($this->translate('totalPrice') . ':', 1520, 705 + ($multiplier * 55), function (Font $font) {
			$font->color($this->template->getPrimaryColor());
			$font->size(27);
			$font->file($this->template->getFont());
		});
		$this->image->text($this->formatNumber($this->getTotalPrice(TRUE)) . ' ' . $this->payment->getCurrency(), 1850, 705 + ($multiplier * 55), function (Font $font) {
			$font->size(27);
			$font->file($this->template->getFont());
			$font->color($this->template->getFontColor());
		});
	}

}
