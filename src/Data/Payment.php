<?php

namespace WebChemistry\Invoice\Data;

use WebChemistry\Invoice\Exception;

class Payment {

	/** @var string */
	protected $accountNumber;

	/** @var string */
	protected $iBan;

	/** @var string */
	protected $swift;

	/** @var float */
	protected $tax = 0.21;

	/** @var string */
	protected $currency;

	/** @var Item[] */
	protected $items = [];

	/** @var int */
	protected $invoiceNumber;

	/** @var \DateTime */
	protected $dueDate;

	/** @var string */
	protected $variableSymbol;

	/** @var string */
	protected $constantSymbol;

	/** @var \DateTime */
	protected $date;

	/**
	 * @param string $currency
	 * @param int $invoiceNumber
	 */
	public function __construct($currency, $invoiceNumber) {
		$this->setCurrency($currency);
		$this->setInvoiceNumber($invoiceNumber);
		$this->date = new \DateTime();
	}

	/**
	 * @return string
	 */
	public function getAccountNumber() {
		return $this->accountNumber;
	}

	/**
	 * @param string $accountNumber
	 * @return self
	 */
	public function setAccountNumber($accountNumber) {
		$this->accountNumber = (string) $accountNumber;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getIBan() {
		return $this->iBan;
	}

	/**
	 * @param string $iBan
	 * @return self
	 */
	public function setIBan($iBan) {
		$this->iBan = (string) $iBan;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getSwift() {
		return $this->swift;
	}

	/**
	 * @param string $swift
	 * @return self
	 */
	public function setSwift($swift) {
		$this->swift = (string) $swift;

		return $this;
	}

	/**
	 * @return float
	 */
	public function getTax() {
		return $this->tax;
	}

	/**
	 * @param float $tax
	 * @throws Exception
	 * @return self
	 */
	public function setTax($tax) {
		if (!is_numeric($tax)) {
			throw new Exception(sprintf('Tax must be numeric, %s given.', gettype($tax)));
		}
		$this->tax = $tax;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getCurrency() {
		return $this->currency;
	}

	/**
	 * @param string $currency
	 * @return self
	 */
	public function setCurrency($currency) {
		$this->currency = (string) $currency;

		return $this;
	}

	/************************* Getters and setters **************************/

	/**
	 * @return Item[]
	 */
	public function getItems() {
		return $this->items;
	}

	/**
	 * @param Item $item
	 * @throws Exception
	 * @return self
	 */
	public function addItem(Item $item) {
		$this->items[] = $item;

		return $this;
	}

	/**
	 * @param Item $item
	 * @return self
	 */
	public function removeItem(Item $item) {
		if (($index = array_search($item, $this->items)) !== FALSE) {
			unset($this->items[$index]);
		}

		return $this;
	}

	/**
	 * @return int
	 */
	public function getInvoiceNumber() {
		return $this->invoiceNumber;
	}

	/**
	 * @param int $invoiceNumber
	 * @throws Exception
	 * @return self
	 */
	public function setInvoiceNumber($invoiceNumber) {
		if (!is_numeric($invoiceNumber)) {
			throw new Exception(sprintf('Invoice number must be numeric, %s given.', gettype($invoiceNumber)));
		}
		$this->invoiceNumber = $invoiceNumber;

		return $this;
	}

	/**
	 * @return \DateTime
	 */
	public function getDueDate() {
		return $this->dueDate;
	}

	/**
	 * @param \DateTime $dueDate
	 * @return self
	 */
	public function setDueDate(\DateTime $dueDate) {
		$this->dueDate = $dueDate;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getVariableSymbol() {
		return $this->variableSymbol;
	}

	/**
	 * @param string $variableSymbol
	 * @throws Exception
	 * @return self
	 */
	public function setVariableSymbol($variableSymbol) {
		if (!is_numeric($variableSymbol)) {
			throw new Exception(sprintf('Variable symbol must be numeric, %s given.', gettype($variableSymbol)));
		}
		$this->variableSymbol = $variableSymbol;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getConstantSymbol() {
		return $this->constantSymbol;
	}

	/**
	 * @param string $constantSymbol
	 * @throws Exception
	 * @return self
	 */
	public function setConstantSymbol($constantSymbol) {
		if (!is_numeric($constantSymbol)) {
			throw new Exception(sprintf('Constant symbol must be numeric, %s given.', gettype($constantSymbol)));
		}
		$this->constantSymbol = $constantSymbol;

		return $this;
	}

	/**
	 * @return \DateTime
	 */
	public function getDate() {
		return $this->date;
	}

	/**
	 * @param \DateTime $date
	 * @return self
	 */
	public function setDate(\DateTime $date) {
		$this->date = $date;

		return $this;
	}

}
