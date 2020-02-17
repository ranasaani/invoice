<?php declare(strict_types = 1);

namespace Contributte\Invoice\Templates;

use Contributte\Invoice\Calculators\ICalculator;
use Contributte\Invoice\Data\Company;
use Contributte\Invoice\Data\Customer;
use Contributte\Invoice\Data\Order;
use Contributte\Invoice\Renderers\IRenderer;

interface ITemplate
{

	public function build(ICalculator $calculator, IRenderer $renderer, Customer $customer, Order $order, Company $company): string;

}
