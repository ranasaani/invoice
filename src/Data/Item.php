<?php

declare(strict_types=1);

namespace WebChemistry\Invoice\Data;

use WebChemistry\Invoice\InvoiceException;

class Item {

	/** @var string */
	protected $name;

	/** @var int|float */
	protected $count;

	/** @var int|float */
	protected $price;

	/**
	 * @param string $name
	 * @param int|float $count
	 * @param int|float $price
	 * @throws InvoiceException
	 */
	public function __construct(string $name, $count, $price) {
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
	public function getName(): string {
		return $this->name;
	}

	/**
	 * @return int|float
	 */
	public function getCount() {
		return $this->count;
	}

	/**
	 * @return int|float
	 */
	public function getPrice() {
		return $this->price;
	}

}
