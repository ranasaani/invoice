<?php

declare(strict_types=1);

namespace WebChemistry\Invoice\Templates;

use Nette\Utils\Strings;
use WebChemistry\Invoice\Components\Paginator;
use WebChemistry\Invoice\Data\Company;
use WebChemistry\Invoice\Data\Customer;
use WebChemistry\Invoice\Data\Order;
use WebChemistry\Invoice\Formatter;
use WebChemistry\Invoice\ITranslator;
use WebChemistry\Invoice\Renderers\Color;
use WebChemistry\Invoice\Renderers\IRenderer;
use WebChemistry\Invoice\Renderers\Settings;
use WebChemistry\Invoice\Translator;

class DefaultTemplate implements ITemplate {

	/** @var Color */
	private $primary;

	/** @var Color */
	private $font;

	/** @var Color */
	private $even;

	/** @var Color */
	private $odd;

	/** @var IRenderer */
	private $renderer;

	/** @var Customer */
	private $customer;

	/** @var Order */
	private $order;

	/** @var Company */
	private $company;

	/** @var ITranslator */
	private $translator;

	/** @var Formatter */
	private $formatter;

	public function __construct(?ITranslator $translator = null, ?Formatter $formatter = null) {
		$this->primary = new Color(6, 178, 194);
		$this->font = new Color(52, 52, 53);
		$this->even = new Color(241, 240, 240);
		$this->odd = Color::white();
		$this->translator = $translator ?: new Translator();
		$this->formatter = $formatter ?: new Formatter();
	}

	public function build(IRenderer $renderer, Customer $customer, Order $order, Company $company): string {
		$this->renderer = $renderer;
		$this->customer = $customer;
		$this->order = $order;
		$this->company = $company;

		$this->renderer->createNew();

		$this->renderer->addFont('sans', 'OpenSans-Regular.php');
		$this->renderer->addFont('sans', 'OpenSans-Semibold.php', Settings::FONT_STYLE_BOLD);
		$this->renderer->addFont('icons', 'pe.php');

		$paginator = new Paginator($this->order->getItems(), 19);

		while ($paginator->nextPage()) {
			if (!$paginator->isFirstPage()) {
				$this->renderer->addPage();
			}

			$this->buildHeader();
			$this->buildLeftPane();
			$this->buildRightPane();
			$this->buildMain();

			$offset = 337;
			$offset = $this->buildItems($offset, $paginator->getItems());

			if ($paginator->isLastPage()) {
				$this->buildTotal($offset);
			}

			$this->buildFooter($paginator);
		}

		return $this->renderer->output();
	}

	protected function buildTotal(int $offset): void {
		$renderer = $this->renderer;
		$half = ($renderer->width() - 553) / 2;

		if ($this->company->hasTax() && $this->order->getPayment()->getTax() !== null) {
			$renderer->rect(553, $offset, $renderer->width() - 553, 29, function (Settings $settings) {
				$settings->setFillDrawColor($this->even);
			});
			$renderer->cell(553, $offset, $half, 29.0, Strings::upper($this->translator->translate('subtotal')) . ':', function (Settings $settings) {
				$settings->fontFamily = 'sans';
				$settings->fontStyle = $settings::FONT_STYLE_BOLD;
				$settings->align = $settings::ALIGN_CENTER;
			});
			$renderer->cell(553 + $half, $offset, $half - 4, 29.0, $this->formatter->formatMoney($this->order->getTotalPrice(false), $this->order->getPayment()
				->getCurrency()), function (Settings $settings) {
				$settings->fontStyle = $settings::FONT_STYLE_NONE;
			});
			$offset += 29;

			$renderer->rect(553, $offset, $renderer->width() - 553, 29, function (Settings $settings) {
				$settings->setFillDrawColor($this->even);
			});
			$renderer->cell(553, $offset, $half, 29.0, Strings::upper($this->translator->translate('tax')) . ':', function (Settings $settings) {
				$settings->fontFamily = 'sans';
				$settings->fontStyle = $settings::FONT_STYLE_BOLD;
				$settings->align = $settings::ALIGN_CENTER;
			});
			$renderer->cell(553 + $half, $offset, $half - 4, 29.0, $this->order->getPayment()->getTax() * 100 . ' %', function (Settings $settings) {
				$settings->fontStyle = $settings::FONT_STYLE_NONE;
			});
			$offset += 29;
		}

		$renderer->rect(553, $offset, $renderer->width() - 553, 29, function (Settings $settings) {
			$settings->setFillDrawColor($this->even);
		});
		$renderer->polygon([
			553 + $half, $offset + 29,
			573 + $half, $offset,
			$renderer->width(), $offset,
			$renderer->width(), $offset + 29
		], function (Settings $settings) {
			$settings->setFillDrawColor($this->primary);
		});
		$renderer->cell(553, $offset, $half, 29.0, Strings::upper($this->translator->translate('totalPrice')) . ':', function (Settings $settings) {
			$settings->fontFamily = 'sans';
			$settings->fontStyle = $settings::FONT_STYLE_BOLD;
			$settings->align = $settings::ALIGN_CENTER;
		});
		$renderer->cell(553 + $half, $offset, $half - 4, 29.0, $this->formatter->formatMoney($this->order->getTotalPrice($this->company->hasTax()), $this->order->getPayment()->getCurrency()), function (Settings $settings) {
			$settings->fontFamily = 'sans';
			$settings->fontStyle = $settings::FONT_STYLE_BOLD;
			$settings->fontColor = Color::white();
		});
	}

	protected function buildItems(int $offset, array $items): int {
		$renderer = $this->renderer;

		foreach ($items as $i => $item) {
			$renderer->rect(0, $offset, $renderer->width(), 29, function (Settings $settings) use ($i) {
				$settings->setFillDrawColor($i % 2 === 1 ? $this->even : $this->odd);
			});
			$renderer->rect(0, $offset + 30, $renderer->width(), 0.1, function (Settings $settings) {
				$settings->setFillDrawColor($this->even->darken(10));
			});

			if ($this->order->hasPriceWithTax() && $this->order->getPayment()->getTax()) {
				$price = $item->getPrice() - ($item->getPrice() / ($this->order->getPayment()->getTax() + 1.0)) * $this->order->getPayment()->getTax();
			} else {
				$price = $item->getPrice();
			}

			// Data
			$renderer->cell(33, $offset, 360, 29.0, $item->getName(), function (Settings $settings) {
				$settings->fontFamily = 'sans';
				$settings->fontColor = $this->font;
				$settings->fontStyle = $settings::FONT_STYLE_NONE;
				$settings->align = $settings::ALIGN_LEFT;
				$settings->fontSize = 5;
			});
			$renderer->cell(393, $offset, 100, 29.0, $this->formatter->formatNumber($item->getCount()), function (Settings $settings) {
				$settings->align = $settings::ALIGN_CENTER;
			});

			$renderer->cell(493, $offset, 140, 29.0, $this->formatter->formatMoney($price, $this->order->getPayment()->getCurrency()));

			$renderer->cell(670, $offset, 123, 29.0, $this->formatter->formatMoney($price * $item->getCount(), $this->order->getPayment()->getCurrency()), function (Settings $settings) {
				$settings->fontFamily = 'sans';
				$settings->fontStyle = $settings::FONT_STYLE_BOLD;
			});

			$offset += 31;
		}

		return $offset;
	}

	protected function buildMain(): void {
		$renderer = $this->renderer;

		$renderer->rect(0, 307, $renderer->width(), 29, function (Settings $settings) {
			$settings->setFillDrawColor($this->even);
		});
		$renderer->polygon([
			$renderer->width(), 307,
			$renderer->width(), 336,
			$renderer->width() - 124, 336,
			$renderer->width() - 100, 307,
		], function (Settings $settings) {
			$settings->setFillDrawColor($this->primary);
		});
		$renderer->rect(0, 336, $renderer->width(), 1);


		$renderer->cell(33, 307, 360, 29.0, Strings::upper($this->translator->translate('item')), function (Settings $settings) {
			$settings->fontColor = $this->primary;
			$settings->fontStyle = $settings::FONT_STYLE_BOLD;
			$settings->fontSize = 9;
		});
		$renderer->cell(393, 307, 100, 29.0, Strings::upper($this->translator->translate('count')), function (Settings $settings) {
			$settings->align = $settings::ALIGN_CENTER;
		});

		$renderer->cell(493, 307, 140, 29.0, Strings::upper($this->translator->translate('pricePerItem')));

		$renderer->cell($renderer->width() - 80, 307, 60, 29.0, Strings::upper($this->translator->translate('total')), function (Settings $settings) {
			$settings->fontColor = $this->even;
		});
		//$renderer->rect(20, 307, 360, 29);
		/*



		$this->image->text(Strings::upper($this->translate('total')), 2250, 1205, function (Font $font) {
			$font->color($this->template->getColorOdd());
			$font->file($this->template->getFontBold());
			$font->valign('center');
			$font->align('center');
			$font->size(37);
		});*/
	}

	protected function buildHeader(): void {
		$renderer = $this->renderer;

		$renderer->polygon([
			0, 0,
			0, 100,
			245, 100,
			312, 0
		], function (Settings $settings) {
			$settings->setFillDrawColor($this->font);
		});

		$renderer->polygon([
			312, 0,
			245, 100,
			$renderer->width(), 100,
			$renderer->height(), 0
		], function (Settings $settings) {
			$settings->setFillDrawColor($this->primary);
		});

		$renderer->polygon([
			0, 100,
			0, 106,
			168, 106,
			188, 120,
			200, 100
		]);

		$renderer->polygon([
			200, 100,
			188, 120,
			205, 125,
			222, 100
		], function (Settings $settings) {
			$settings->setFillDrawColor($this->primary->darken(15));
		});

		$renderer->polygon([
			222, 100,
			217, 106,
			242, 106,
			247, 100
		], function (Settings $settings) {
			$settings->setFillDrawColor($this->primary);
		});

		$y = 30;
		$renderer->cell(112, $y, 1, null, $this->company->getZip() . ' ' . $this->company->getTown(), function (Settings $settings) {
			$settings->align = $settings::ALIGN_RIGHT;
			$settings->fontSize = 6;
			$settings->fontFamily = 'sans';
			$settings->fontColor = Color::white();
		});
		$mul = 1;
		if ($this->company->getAddress()) {
			$renderer->cell(112, $y + 12, 1, null, $this->company->getAddress());
			$mul++;
		}
		if ($this->company->getCountry()) {
			$renderer->cell(112, $y + (12 * $mul), 1, null, $this->company->getCountry());
		}

		$multiplier = 0;
		if ($this->company->getTin()) {
			$renderer->cell(130, $y, 1, null, Strings::upper($this->translator->translate('vat')) . ': ' . $this->company->getTin(), function (Settings $settings) {
				$settings->align = $settings::ALIGN_LEFT;
			});
			$multiplier++;
		}

		if ($this->company->getVaTin()) {
			$renderer->cell(130, $y + ($multiplier * 12), 1, null, Strings::upper($this->translator->translate('vaTin')) . ': ' . $this->company->getVaTin(), function (Settings $settings) {
				$settings->align = $settings::ALIGN_LEFT;
			});
			$multiplier++;
		}

		$renderer->cell(130, $y + ($multiplier * 12), 1, null,
			$this->company->hasTax() ? $this->translator->translate('taxPay') : $this->translator->translate('notTax'));

		$renderer->cell(543, 25, 1, null, $this->company->getName(), function (Settings $settings) {
			$settings->align = $settings::ALIGN_CENTER;
			$settings->fontSize = 8;
		});

		$renderer->cell(525, 45, 1, null, $this->translator->translate('date') . ': ' . $this->formatter->formatDate($this->order->getCreated()), function (Settings $settings) {
			$settings->align = $settings::ALIGN_RIGHT;
			$settings->fontSize = 6;
		});
		$renderer->cell(525, 57, 1, null, $this->translator->translate('invoiceNumber') . ': ' . $this->order->getNumber());
		$renderer->cell(537, 53, 1, null, Strings::upper($this->translator->translate('invoice')), function (Settings $settings) {
			$settings->align = $settings::ALIGN_LEFT;
			$settings->fontSize = 18;
			$settings->fontStyle = $settings::FONT_STYLE_BOLD;
		});
	}

	protected function buildLeftPane(): void {
		$renderer = $this->renderer;
		$text = Strings::upper($this->translator->translate('subscriber'));

		$renderer->cell(33, 175, 1, null, $text, function (Settings $settings) {
			$settings->fontSize = 16;
			$settings->fontColor = $this->primary;
			$settings->fontStyle = $settings::FONT_STYLE_BOLD;
		});

		$x = $renderer->textWidth($text) + 76;

		$x = max($x, $renderer->textWidth($this->customer->getName(), function (Settings $settings) {
			$settings->fontSize = 7;
		}) + 76);

		$renderer->polygon([
			$x, 190,
			$x, 200,
			$x - 7, 192,
			0, 192,
			0, 190,
		], function (Settings $settings) {
			$settings->setFillDrawColor($this->primary);
		});

		$renderer->cell(33, 210, 1, null, $this->customer->getName(), function (Settings $settings) {
			$settings->fontSize = 10;
			$settings->fontColor = $this->font;
		});

		$multiplier = 0;
		$renderer->cell(33, 228, 1, null, $this->customer->getZip() . ', ' . $this->customer->getTown(), function (Settings $settings) {
			$settings->fontSize = 6;
			$settings->fontStyle = $settings::FONT_STYLE_NONE;
		});
		$multiplier++;

		if ($this->customer->getAddress()) {
			$renderer->cell(33, 228 + ($multiplier * 10), 1, null, $this->customer->getAddress());
			$multiplier++;
		}

		if ($this->customer->getCountry()) {
			$renderer->cell(33, 228 + ($multiplier * 10), 1, null, $this->customer->getCountry());
			$multiplier++;
		}

		if ($this->customer->getTin()) {
			$renderer->cell(33, 228 + ($multiplier * 10), 1, null, Strings::upper($this->translator->translate('vat')) . ': ' . $this->customer->getTin());
			$multiplier++;
		}

		if ($this->customer->getVaTin()) {
			$renderer->cell(33, 228 + ($multiplier * 10), 1, null, Strings::upper($this->translator->translate('vaTin')) . ': ' . $this->customer->getVaTin());
		}
	}

	protected function buildRightPane(): void {
		$renderer = $this->renderer;

		$renderer->cell(450, 145, 1, null, Strings::upper($this->translator->translate('paymentData')), function (Settings $settings) {
			$settings->fontColor = $this->primary;
			$settings->align = $settings::ALIGN_LEFT;
			$settings->fontStyle = $settings::FONT_STYLE_BOLD;
			$settings->fontSize = 16;
		});

		$renderer->polygon([
			440, 160,
			440, 170,
			452, 162,
			$renderer->width(), 162,
			$renderer->width(), 160
		]);

		$iconCb = function (Settings $settings) {
			$settings->fontStyle = $settings::FONT_STYLE_NONE;
			$settings->fontColor = $this->primary;
			$settings->fontSize = 9;
			$settings->fontFamily = 'icons';
		};
		$sectionCb = function (Settings $settings) {
			$settings->fontSize = 6;
			$settings->fontFamily = 'sans';
		};
		$textCb = function (Settings $settings) {
			$settings->fontColor = $this->font;
		};

		$multiplier = 0;
		// Account information
		if ($this->order->getAccount() !== null) {
			if ($this->order->getDueDate() !== null) {
				$renderer->cell(450, 190, 1, null, 'a', $iconCb);
				$renderer->cell(465, 189, 1, null, Strings::upper($this->translator->translate('dueDate')) . ':', $sectionCb);
				$renderer->cell(465 + 100, 189, 1, null, $this->formatter->formatDate($this->order->getDueDate()), $textCb);

				$multiplier++;
			}

			if ($this->order->getAccount()->getAccountNumber()) {
				$renderer->cell(450, 190 + ($multiplier * 15), 1, null, 'b', $iconCb);
				$renderer->cell(465, 189 + ($multiplier * 15), 1, null, Strings::upper($this->translator->translate('accountNumber')) . ':', $sectionCb);
				$renderer->cell(465 + 100, 189 + ($multiplier * 15), 1, null, $this->order->getAccount()->getAccountNumber(), $textCb);
				$multiplier++;
			}

			if ($this->order->getAccount()->getIBan()) {
				$renderer->cell(450, 190 + ($multiplier * 15), 1, null, 'b', $iconCb);
				$renderer->cell(465, 189 + ($multiplier * 15), 1, null, Strings::upper($this->translator->translate('iban')) . ':', $sectionCb);
				$renderer->cell(465 + 100, 189 + ($multiplier * 15), 1, null, $this->order->getAccount()->getIBan(), $textCb);
				$multiplier++;
			}
		}

		// Payment
		if ($this->order->getPayment()->getVariableSymbol()) {
			$renderer->cell(453, 190 + ($multiplier * 15), 1, null, 'c', $iconCb);
			$renderer->cell(465, 189 + ($multiplier * 15), 1, null, Strings::upper($this->translator->translate('varSymbol')) . ':', $sectionCb);
			$renderer->cell(465 + 100, 189 + ($multiplier * 15), 1, null, $this->order->getPayment()->getVariableSymbol(), $textCb);
			$multiplier++;
		}

		if ($this->order->getPayment()->getConstantSymbol()) {
			$renderer->cell(453, 190 + ($multiplier * 15), 1, null, 'c', $iconCb);
			$renderer->cell(465, 189 + ($multiplier * 15), 1, null, Strings::upper($this->translator->translate('constSymbol')) . ':', $sectionCb);
			$renderer->cell(465 + 100, 189 + ($multiplier * 15), 1, null, $this->order->getPayment()->getConstantSymbol(), $textCb);
			$multiplier++;
		}

		// Total price
		$renderer->cell(450, 190 + ($multiplier * 15), 1, null, 'd', $iconCb);
		$renderer->cell(465, 189 + ($multiplier * 15), 1, null, Strings::upper($this->translator->translate('totalPrice')) . ':', $sectionCb);
		$renderer->cell(465 + 100, 189 + ($multiplier * 15), 1, null, $this->formatter->formatMoney($this->order->getTotalPrice($this->company->hasTax()), $this->order->getPayment()->getCurrency()), $textCb);
	}

	protected function buildFooter(Paginator $paginator): void {
		$renderer = $this->renderer;

		$renderer->rect(0, $renderer->height() - 20, $renderer->width(), 20, function (Settings $settings) {
			$settings->setFillDrawColor($this->font);
		});

		$renderer->cell(0, -10, $renderer->width() - 10, null, $this->translator->translate('page') . ' ' . $paginator->getCurrentPage() . ' / ' . $paginator->getTotalPages(), function (Settings $settings) {
			$settings->fontColor = Color::white();
			$settings->fontFamily = 'sans';
			$settings->align = $settings::ALIGN_RIGHT;
			$settings->fontSize = 6;
		});

/*
		$this->image->rectangle(0, $this->image->getHeight() - 80, $this->image->getWidth(), $this->image->getHeight(), function (AbstractShape $shape) {
			$shape->background($this->template->getFontColor());
		});

		$this->image->text($this->template->getFooter(), (int) ($this->image->getWidth() / 2), $this->image->getHeight() - 40, function (Font $font) {
			$font->file($this->template->getFont());
			$font->color($this->template->getColorOdd());
			$font->align('center');
			$font->size(24);
			$font->valign('center');
		});*/
	}

}
