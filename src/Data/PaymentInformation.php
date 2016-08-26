<?php

namespace WebChemistry\Invoice\Data;

use WebChemistry\Invoice\InvoiceException;

class PaymentInformation {

	/** @var string */
	private $currency;

	/** @var string */
	private $variableSymbol;

	/** @var string */
	private $constantSymbol;

	/** @var float|null */
	private $tax;

	/**
	 * @param string $currency
	 * @param string|null $variableSymbol
	 * @param string|null $constantSymbol
	 * @param float|null $tax
	 */
	public function __construct($currency, $variableSymbol = NULL, $constantSymbol = NULL, $tax = NULL) {
		$this->currency = $currency;
		$this->variableSymbol = $variableSymbol;
		$this->constantSymbol = $constantSymbol;
		$this->tax = $tax;

		$this->validate();
	}

	/**
	 * @throws InvoiceException
	 */
	private function validate() {
		if (!$this->currency || !is_string($this->currency)) {
			throw InvoiceException::wrongType('non-empty string', $this->currency);
		}
		if ($this->tax && !is_float($this->tax)) {
			throw InvoiceException::wrongType('float', $this->tax);
		}
	}

	/////////////////////////////////////////////////////////////////

	/**
	 * @return string
	 */
	public function getCurrency() {
		return $this->currency;
	}

	/**
	 * @return string
	 */
	public function getVariableSymbol() {
		return $this->variableSymbol;
	}

	/**
	 * @return string
	 */
	public function getConstantSymbol() {
		return $this->constantSymbol;
	}

	/**
	 * @return float|null
	 */
	public function getTax() {
		return $this->tax;
	}

}
