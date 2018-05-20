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
	public function __construct(string $name, $price, $count) {
		$this->name = $name;
		$this->count = $count;
		$this->price = $price;
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
