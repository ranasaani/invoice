<?php

namespace WebChemistry\Invoice\Data;

use WebChemistry\Invoice\InvoiceException;

class Account {

	/** @var string */
	private $accountNumber;

	/** @var string|null */
	private $iBan;

	/** @var string|null */
	private $swift;

	/**
	 * @param string $accountNumber
	 * @param string|null $iBan
	 * @param string|null $swift
	 */
	public function __construct($accountNumber, $iBan = NULL, $swift = NULL) {
		$this->accountNumber = $accountNumber;
		$this->iBan = $iBan;
		$this->swift = $swift;

		$this->validate();
	}

	/**
	 * Validates properties
	 *
	 * @throws InvoiceException
	 */
	private function validate() {
		if (!$this->accountNumber || !is_string($this->accountNumber)) {
			throw InvoiceException::wrongType('non-empty string', $this->accountNumber);
		}
		if ($this->iBan !== NULL && !$this->iBan || !is_string($this->iBan)) {
			throw InvoiceException::wrongType('non-empty string or null', $this->iBan);
		}
		if ($this->swift !== NULL && !$this->swift || !is_string($this->swift)) {
			throw InvoiceException::wrongType('non-empty string or null', $this->iBan);
		}
	}

	/////////////////////////////////////////////////////////////////

	/**
	 * @return string
	 */
	public function getAccountNumber() {
		return $this->accountNumber;
	}

	/**
	 * @return null|string
	 */
	public function getIBan() {
		return $this->iBan;
	}

	/**
	 * @return null|string
	 */
	public function getSwift() {
		return $this->swift;
	}

}
