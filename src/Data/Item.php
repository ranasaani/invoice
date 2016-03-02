<?php

namespace WebChemistry\Invoice\Data;

use WebChemistry\Invoice\Exception;

class Item {

	/** @var string */
	protected $name;

	/** @var int */
	protected $count;

	/** @var int */
	protected $price;

	/** @var array */
	private $important = ['name', 'count', 'price'];

	/**
	 * @throws Exception
	 */
	public function check() {
		foreach ($this->important as $item) {
			if (!$this->$item) {
				throw new Exception("Parameter '$item' must be set.");
			}
		}
	}

	/**
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @param string $name
	 * @return self
	 */
	public function setName($name) {
		$this->name = (string) $name;

		return $this;
	}

	/**
	 * @return int
	 */
	public function getCount() {
		return $this->count;
	}

	/**
	 * @param int $count
	 * @throws Exception
	 * @return self
	 */
	public function setCount($count) {
		if (!is_numeric($count)) {
			throw new Exception(sprintf('Count must be numeric, %s given.', gettype($count)));
		}
		$this->count = $count;

		return $this;
	}

	/**
	 * @return int
	 */
	public function getPrice() {
		return $this->price;
	}

	/**
	 * @param int $price
	 * @throws Exception
	 * @return self
	 */
	public function setPrice($price) {
		if (!is_numeric($price)) {
			throw new Exception(sprintf('Price must be numeric, %s given.', gettype($price)));
		}
		$this->price = $price;

		return $this;
	}

}
