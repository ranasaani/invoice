<?php

declare(strict_types=1);

namespace WebChemistry\Invoice\Data;

use Nette\SmartObject;

class PaymentInformation {

	use SmartObject;

	/** @var string */
	private $currency;

	/** @var string|null */
	private $variableSymbol;

	/** @var string|null */
	private $constantSymbol;

	/** @var float|null */
	private $tax;

	public function __construct(string $currency, ?string $variableSymbol = null, ?string $constantSymbol = null, ?float $tax = null) {
		$this->currency = $currency;
		$this->variableSymbol = $variableSymbol;
		$this->constantSymbol = $constantSymbol;
		$this->tax = $tax;
	}

	public function getCurrency(): string {
		return $this->currency;
	}

	public function getVariableSymbol(): ?string {
		return $this->variableSymbol;
	}

	public function getConstantSymbol(): ?string {
		return $this->constantSymbol;
	}

	public function getTax(): ?float {
		return $this->tax;
	}

}
