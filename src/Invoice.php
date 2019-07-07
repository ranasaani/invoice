<?php declare(strict_types = 1);

namespace Contributte\Invoice;

use Contributte\Invoice\Calculators\FloatCalculator;
use Contributte\Invoice\Calculators\ICalculator;
use Contributte\Invoice\Data\Company;
use Contributte\Invoice\Data\Customer;
use Contributte\Invoice\Data\Order;
use Contributte\Invoice\Renderers\IRenderer;
use Contributte\Invoice\Renderers\PDFRenderer;
use Contributte\Invoice\Responses\PdfResponse;
use Contributte\Invoice\Templates\DefaultTemplate;
use Contributte\Invoice\Templates\ITemplate;
use Nette\Application\IResponse;
use Nette\SmartObject;

class Invoice
{

	use SmartObject;

	/** @var Company */
	protected $company;

	/** @var ITemplate */
	private $template;

	/** @var IRenderer */
	private $renderer;

	/** @var ICalculator */
	private $calculator;

	public function __construct(Company $company, ?ITemplate $template = null, ?IRenderer $renderer = null, ?ICalculator $calculator = null)
	{
		$this->company = $company;
		$this->template = $template ?: new DefaultTemplate();
		$this->renderer = $renderer ?: new PDFRenderer();
		$this->calculator = $calculator ?: new FloatCalculator();
	}

	public function create(Customer $customer, Order $order): string
	{
		return $this->template->build($this->calculator, $this->renderer, $customer, $order, $this->company);
	}

	public function send(Customer $customer, Order $order): void
	{
		header('Content-type: application/pdf');

		echo $this->create($customer, $order);
	}

	public function createResponse(Customer $customer, Order $order): IResponse
	{
		return new PdfResponse($this->create($customer, $order));
	}

}
