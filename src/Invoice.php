<?php

declare(strict_types=1);

namespace WebChemistry\Invoice;

use Nette\SmartObject;
use WebChemistry\Invoice\Calculators\FloatCalculator;
use WebChemistry\Invoice\Calculators\ICalculator;
use WebChemistry\Invoice\Data\Account;
use WebChemistry\Invoice\Data\Company;
use WebChemistry\Invoice\Data\Customer;
use WebChemistry\Invoice\Data\Order;
use WebChemistry\Invoice\Data\PaymentInformation;
use WebChemistry\Invoice\Renderers\IRenderer;
use WebChemistry\Invoice\Renderers\PDFRenderer;
use WebChemistry\Invoice\Templates\DefaultTemplate;
use WebChemistry\Invoice\Templates\ITemplate;

class Invoice {

	use SmartObject;

	/** @var Company */
	protected $company;

	/** @var ITemplate */
	private $template;

	/** @var IRenderer */
	private $renderer;

	/** @var ICalculator */
	private $calculator;

	public function __construct(Company $company, ?ITemplate $template = null, ?IRenderer $renderer = null, ?ICalculator $calculator = null) {
		$this->company = $company;
		$this->template = $template ?: new DefaultTemplate();
		$this->renderer = $renderer ?: new PDFRenderer();
		$this->calculator = $calculator ?: new FloatCalculator();
	}

	public function generatePreview(): string {
		$tax = $this->company->hasTax() ? 0.21 : null;
		$customer = new Customer('John Doe', 'Los Angeles', 'Cavetown', '720 55', 'USA', '08304431', 'CZ08304431');
		$account = new Account('2353462013/0800', 'CZ4808000000002353462013', 'GIGACZPX');
		$paymentInfo = new PaymentInformation('$', '0123456789', '1234', $tax);

		$order = new Order(date('Y') . '0001', new \DateTime('+ 7 days'), $account, $paymentInfo);
		$order->addItem('Logitech G700s Rechargeable Gaming Mouse', 1790, 4);
		$order->addItem('ASUS Z380KL 8" - 16GB, LTE, bílá', 6490, 1);
		$order->addItem('Philips 48PFS6909 - 121cm', 13990, 1);
		$order->addItem('HP Deskjet 3545 Advantage', 1799, 1);
		$order->addItem('LG 105UC9V - 266cm', 11599, 2);

		return $this->create($customer, $order);
	}

	public function create(Customer $customer, Order $order): string {
		return $this->template->build($this->calculator, $this->renderer, $customer, $order, $this->company);
	}

}
