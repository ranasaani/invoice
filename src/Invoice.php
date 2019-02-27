<?php

declare(strict_types=1);

namespace WebChemistry\Invoice;

use Nette\Application\IResponse;
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
use WebChemistry\Invoice\Responses\PdfResponse;
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

	public function create(Customer $customer, Order $order): string {
		return $this->template->build($this->calculator, $this->renderer, $customer, $order, $this->company);
	}

	public function send(Customer $customer, Order $order): void {
		header('Content-type: application/pdf');

		echo $this->create($customer, $order);
	}

	public function createResponse(Customer $customer, Order $order): IResponse {
		return new PdfResponse($this->create($customer, $order));
	}

}
