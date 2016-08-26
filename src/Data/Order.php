<?php

namespace WebChemistry\Invoice\Data;

use WebChemistry\Invoice\InvoiceException;

class Order {

	/** @var string|int */
	private $number;

	/** @var \DateTime */
	private $dueDate;

	/** @var Account */
	private $account;

	/** @var PaymentInformation */
	private $payment;

	/** @var \DateTime */
	private $created;

	/** @var Item[] */
	private $items = [];

	/**
	 * @param string|int $number
	 * @param \DateTime $dueDate
	 * @param Account $account
	 * @param PaymentInformation $payment
	 * @param \DateTime|NULL $created
	 */
	public function __construct($number, \DateTime $dueDate, Account $account, PaymentInformation $payment,
								\DateTime $created = NULL) {
		$this->number = $number;
		$this->dueDate = $dueDate;
		$this->account = $account;
		$this->payment = $payment;
		$this->created = $created ? : new \DateTime();

		$this->validate();
	}

	/**
	 * @param string $name
	 * @param int|float $price
	 * @param int|float $count
	 * @return Item
	 */
	public function addItem($name, $price, $count = 1) {
		return $this->items[] = new Item($name, $price, $count);
	}

	/**
	 * Validates properties
	 *
	 * @throws InvoiceException
	 */
	private function validate() {
		if (!$this->number || !is_string($this->number) || !is_numeric($this->number)) {
			throw InvoiceException::wrongType('non-empty string or numeric', $this->number);
		}
	}

	/////////////////////////////////////////////////////////////////

	/**
	 * @return int|string
	 */
	public function getNumber() {
		return $this->number;
	}

	/**
	 * @return \DateTime
	 */
	public function getDueDate() {
		return $this->dueDate;
	}

	/**
	 * @return Account
	 */
	public function getAccount() {
		return $this->account;
	}

	/**
	 * @return PaymentInformation
	 */
	public function getPayment() {
		return $this->payment;
	}

	/**
	 * @return \DateTime
	 */
	public function getCreated() {
		return $this->created;
	}

	/**
	 * @return Item[]
	 */
	public function getItems() {
		return $this->items;
	}

}
