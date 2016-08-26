<?php

namespace WebChemistry\Invoice\Data;

class Company extends Subject {

	/** @var bool */
	protected $hasTax;

	/**
	 * @param string $name
	 * @param string $town
	 * @param string $address
	 * @param string $zip
	 * @param string $country
	 * @param string|null $tin
	 * @param string|null $vaTin
	 * @param bool $hasTax
	 */
	public function __construct($name, $town, $address, $zip, $country, $tin = NULL, $vaTin = NULL,
								$hasTax = FALSE) {
		parent::__construct($name, $town, $address, $zip, $country, $tin, $vaTin);
		$this->hasTax = (bool) $hasTax;
	}

	/**
	 * @return bool
	 */
	public function hasTax() {
		return $this->hasTax;
	}

}
