<?php

declare(strict_types=1);

namespace WebChemistry\Invoice\Data;

class PaymentInformation {

	/** @var string */
	private $currency;

	/** @var string|null */
	private $variableSymbol;

	/** @var string|null */
	private $constantSymbol;

	/** @var float|null */
	private $tax;

	/**
	 * @param string $currency
	 * @param string|null $variableSymbol
	 * @param string|null $constantSymbol
	 * @param float|null $tax
	 */
	public function __construct(string $currency, ?string $variableSymbol = null, ?string $constantSymbol = null, ?float $tax = null) {
		$this->currency = $currency;
		$this->variableSymbol = $variableSymbol;
		$this->constantSymbol = $constantSymbol;
		$this->tax = $tax;
	}

	/**
	 * @return string
	 */
	public function getCurrency(): string {
		return $this->currency;
	}

	/**
	 * @return string|null
	 */
	public function getVariableSymbol(): ?string {
		return $this->variableSymbol;
	}

	/**
	 * @return string|null
	 */
	public function getConstantSymbol(): ?string {
		return $this->constantSymbol;
	}

	/**
	 * @return float|null
	 */
	public function getTax(): ?float {
		return $this->tax;
	}

}
