<?php

namespace WebChemistry\Invoice\Data;

use WebChemistry\Invoice\InvoiceException;

class Item {

	/** @var string */
	protected $name;

	/** @var int */
	protected $count;

	/** @var int */
	protected $price;

	/**
	 * @param string $name
	 * @param int $count
	 * @param int $price
	 * @throws InvoiceException
	 */
	public function __construct($name, $count, $price) {
		$this->name = $name;
		$this->count = $count;
		$this->price = $price;

		$this->validate();
	}

	/**
	 * Validates properties
	 *
	 * @throws InvoiceException
	 */
	private function validate() {
		if (!$this->name || !is_string($this->name)) {
			throw InvoiceException::wrongType('non-empty string', $this->name);
		}
		if (!is_numeric($this->count)) {
			throw InvoiceException::wrongType('numeric', $this->count);
		}
		if (!is_numeric($this->price)) {
			throw InvoiceException::wrongType('numeric', $this->price);
		}
	}

	/////////////////////////////////////////////////////////////////

	/**
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @return int
	 */
	public function getCount() {
		return $this->count;
	}

	/**
	 * @return int
	 */
	public function getPrice() {
		return $this->price;
	}

}
