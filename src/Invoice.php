<?php

namespace WebChemistry\Invoice;

use Intervention\Image\AbstractShape;
use Intervention\Image\Gd\Font;
use Intervention\Image\Image;
use Intervention\Image\ImageManagerStatic;
use WebChemistry\Invoice\Components\IPaginator;
use WebChemistry\Invoice\Components\IPaginatorFactory;
use WebChemistry\Invoice\Components\PaginatorFactory;
use WebChemistry\Invoice\Data\Company;
use WebChemistry\Invoice\Data\Customer;
use WebChemistry\Invoice\Data\Item;
use WebChemistry\Invoice\Data\Order;
use WebChemistry\Invoice\Data\Template;
use Nette\Utils\Strings;

class Invoice {

	/** @var Company */
	protected $company;

	/** @var Template */
	private $template;
	
	/** @var Order */
	private $order;
	
	/** @var Customer */
	private $customer;
	
	/** @var Image */
	private $image;
	
	/** @var Translator */
	private $translator;

	/** @var IPaginatorFactory */
	private $paginatorFactory;

	/** @var IFormatter */
	private $formatter;

	/**
	 * @param Company $company
	 * @param Template $template
	 * @param ITranslator $translator
	 * @param IPaginatorFactory $paginatorFactory
	 * @param IFormatter|null $formatter
	 */
	public function __construct(Company $company, Template $template = NULL, ITranslator $translator = NULL,
								IPaginatorFactory $paginatorFactory = NULL, IFormatter $formatter = NULL) {
		$this->company = $company;
		$this->template = $template ? : new Template();
		$this->translator = $translator ? : new Translator();
		$this->paginatorFactory = $paginatorFactory ? : new PaginatorFactory();
		$this->formatter = $formatter ? : new Formatter();
	}

	/**
	 * @param string $message
	 * @return string
	 */
	protected function translate($message) {
		return $this->translator->translate($message);
	}

	/************************* Rendering **************************/

	/**
	 * @return string Encoded invoice
	 */
	public function generatePreview() {
		$factory = new InvoiceFactory();

		$tax = $this->company->hasTax() ? 0.21 : NULL;
		$customer = $factory->createCustomer('John Doe', 'Los Angeles', 'Cavetown', '720 55', 'USA', '08304431', 'CZ08304431');

		$account = $factory->createAccount('2353462013/0800', 'CZ4808000000002353462013', 'GIGACZPX');
		$paymentInfo = $factory->createPaymentInformation('$', '0123456789', '1234', $tax);

		$order = $factory->createOrder(date('Y') . '0001', new \DateTime('+ 7 days'), $account, $paymentInfo);
		$order->addItem('Logitech G700s Rechargeable Gaming Mouse', 4, 1790);
		$order->addItem('ASUS Z380KL 8" - 16GB, LTE, bílá', 1, 6490);
		$order->addItem('Philips 48PFS6909 - 121cm', 1, 13990);
		$order->addItem('HP Deskjet 3545 Advantage', 1, 1799);
		$order->addItem('LG 105UC9V - 266cm', 2, 11599);

		$images = $this->create($customer, $order);

		return $images[0]->encode();
	}

	/**
	 * @param Customer $customer
	 * @param Order $order
	 * @throws InvoiceException
	 * @return Image[]
	 */
	public function create(Customer $customer, Order $order) {
		$this->customer = $customer;
		$this->order = $order;
		$paginator = $this->paginatorFactory->createPaginator($order->getItems());
		$pages = [];

		while ($paginator->hasNextPage()) {
			$this->initialize();

			$this->rightHeader();
			$this->footer($paginator);
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
				if ($this->company->hasTax() && $order->getPayment()->getTax() !== NULL) {
					$this->tax($plus);
				}

				if ($paginator->isLastPage()) {
					$this->totalPrice($plus);
				}
			}

			$pages[] = $this->image;
		}
		$this->image = NULL;

		return $pages;
	}

	// Template

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
		$this->image->text($this->translate('date') . ': ' . $this->formatter->formatDate($this->order->getCreated()), 1700, 180, function (Font $font) {
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

		$this->image->text($this->translate('invoiceNumber') . ': ' . $this->order->getNumber(), 1700, 230, function (Font $font) {
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
		if ($this->template->getLogo()) {
			$size = @getimagesize($this->template->getLogo());

			if ($size) {
				$this->image->insert($this->template->getLogo(), 'top-left', (int) ((980 - $size[0]) / 2), (int) ((186 - $size[1]) / 2));
			}
		}

		$y = isset($size) ? 220 : 120;

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

		$this->image->text($this->company->hasTax() ? $this->translate('taxPay') : $this->translate('notTax'),
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

		$this->image->text($this->template->getFooter(), $this->image->getWidth() / 2, $this->image->getHeight() - 40, function (Font $font) {
			$font->file($this->template->getFont());
			$font->color($this->template->getColorOdd());
			$font->align('center');
			$font->size(24);
			$font->valign('center');
		});
	}

	protected function footer(IPaginator $paginator) {
		$this->image->text($this->translate('page') . ' ' . $paginator->getCurrentPage() . ' ' . $this->translate('from') . ' ' . $paginator->getTotalPages(),
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

		$this->image->text($this->formatter->formatMoney($this->getTotalPrice(TRUE), $this->order->getPayment()->getCurrency()), 2260, $plus + 65, function (Font $font) {
			$font->align('center');
			$font->color($this->template->getColorOdd());
			$font->file($this->template->getFontBold());
			$font->size(27);
		});

		return $plus + 100;
	}

	/**
	 * @param bool $useTax
	 * @return int
	 */
	private function getTotalPrice($useTax = FALSE) {
		$total = 0;
		if ($useTax && $this->company->hasTax() && $this->order->getPayment()->getTax() !== NULL) {
			$tax = $this->order->getPayment()->getTax() + 1;
		} else {
			$tax = 1;
		}

		foreach ($this->order->getItems() as $item) {
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

		$this->image->text($this->formatter->formatMoney($this->getTotalPrice(FALSE), $this->order->getPayment()->getCurrency()), 2260, $plus + 65, function (Font $font) {
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

		$this->image->text($this->order->getPayment()->getTax() * 100 . ' %', 2260, $plus + 65, function (Font $font) {
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

		$this->image->text($this->formatter->formatNumber($item->getCount(), 0), 1255, 1350 + $plus, function (Font $font) {
			$font->size(27);
			$font->file($this->template->getFont());
			$font->valign('center');
			$font->align('center');
			$font->color($this->template->getFontColor());
		});

		$this->image->text($this->formatter->formatMoney($item->getPrice(), $this->order->getPayment()->getCurrency()), 1650, 1350 + $plus, function (Font $font) {
			$font->size(27);
			$font->file($this->template->getFont());
			$font->valign('center');
			$font->align('center');
			$font->color($this->template->getFontColor());
		});

		$this->image->text($this->formatter->formatMoney($item->getCount() * $item->getPrice(), $this->order->getPayment()->getCurrency()), 2322, 1350 + $plus, function (Font $font) {
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

		if ($this->order->getDueDate()) {
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
			$this->image->text($this->formatter->formatDate($this->order->getDueDate()), 1850, 705 + ($multiplier * 55), function (Font $font) {
				$font->size(27);
				$font->file($this->template->getFont());
				$font->color($this->template->getFontColor());
			});
			$multiplier++;
		}

		if ($this->order->getAccount()->getAccountNumber()) {
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
			$this->image->text($this->order->getAccount()->getAccountNumber(), 1850, 705 + ($multiplier * 55), function (Font $font) {
				$font->size(27);
				$font->file($this->template->getFont());
				$font->color($this->template->getFontColor());
			});
			$multiplier++;
		}

		if ($this->order->getAccount()->getIBan()) {
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
			$this->image->text($this->order->getAccount()->getIBan(), 1850, 705 + ($multiplier * 55), function (Font $font) {
				$font->size(27);
				$font->file($this->template->getFont());
				$font->color($this->template->getFontColor());
			});
			$multiplier++;
		}

		if ($this->order->getAccount()->getSwift()) {
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
			$this->image->text($this->order->getAccount()->getSwift(), 1850, 705 + ($multiplier * 55), function (Font $font) {
				$font->size(27);
				$font->file($this->template->getFont());
				$font->color($this->template->getFontColor());
			});
			$multiplier++;
		}

		if ($this->order->getPayment()->getVariableSymbol()) {
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
			$this->image->text($this->order->getPayment()->getVariableSymbol(), 1850, 705 + ($multiplier * 55), function (Font $font) {
				$font->size(27);
				$font->file($this->template->getFont());
				$font->color($this->template->getFontColor());
			});
			$multiplier++;
		}

		if ($this->order->getPayment()->getConstantSymbol()) {
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
			$this->image->text($this->order->getPayment()->getConstantSymbol(), 1850, 705 + ($multiplier * 55), function (Font $font) {
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
		$this->image->text($this->formatter->formatMoney($this->getTotalPrice(TRUE), $this->order->getPayment()->getCurrency()), 1850, 705 + ($multiplier * 55), function (Font $font) {
			$font->size(27);
			$font->file($this->template->getFont());
			$font->color($this->template->getFontColor());
		});
	}

}
