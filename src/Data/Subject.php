<?php

namespace WebChemistry\Invoice\Data;

use WebChemistry\Invoice\InvoiceException;

abstract class Subject {

	/** @var string */
	protected $name;

	/** @var string */
	protected $town;

	/** @var string */
	protected $address;

	/** @var string */
	protected $zip;

	/** @var string */
	protected $country;

	/** @var string */
	protected $tin;

	/** @var string */
	protected $vaTin;

	/**
	 * @param string $name
	 * @param string $town
	 * @param string $address
	 * @param string $zip
	 * @param string $country
	 * @param string|null $tin
	 * @param string|null $vaTin
	 * @throws InvoiceException
	 */
	public function __construct($name, $town, $address, $zip, $country, $tin = NULL, $vaTin = NULL) {
		$this->name = $name;
		$this->town = $town;
		$this->address = $address;
		$this->zip = $zip;
		$this->country = $country;
		$this->tin = $tin;
		$this->vaTin = $vaTin;

		$this->validate();
	}

	/**
	 * Validates properties
	 *
	 * @throws InvoiceException
	 */
	private function validate() {
		foreach (['name', 'town', 'address', 'zip', 'country'] as $val) {
			if (!$this->$val || !is_string($this->$val)) {
				throw InvoiceException::wrongType('non-empty string', $this->$val);
			}
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
	 * @return string
	 */
	public function getTown() {
		return $this->town;
	}

	/**
	 * @return string
	 */
	public function getAddress() {
		return $this->address;
	}

	/**
	 * @return string
	 */
	public function getZip() {
		return $this->zip;
	}

	/**
	 * @return string
	 */
	public function getCountry() {
		return $this->country;
	}

	/**
	 * @return string
	 */
	public function getTin() {
		return $this->tin;
	}

	/**
	 * @return string
	 */
	public function getVaTin() {
		return $this->vaTin;
	}

}
