<?php

namespace WebChemistry\Invoice\Data;

use Nette\Object;
use WebChemistry\Invoice\Exception;

abstract class AbstractData extends Object {

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

	/** @var array */
	private $important = ['name', 'town', 'address', 'zip', 'country'];

	/**
	 * @throws Exception
	 */
	public function check() {
		foreach ($this->important as $item) {
			if (!$this->$item) {
				throw new Exception("Parameter '$item' must be set.");
			}
		}
		if ($this->vaTin && !$this->tin) {
			throw new Exception("Parameter 'ic' must be set.");
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
	 * @return string
	 */
	public function getTown() {
		return $this->town;
	}

	/**
	 * @param string $town
	 * @return self
	 */
	public function setTown($town) {
		$this->town = (string) $town;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getAddress() {
		return $this->address;
	}

	/**
	 * @param string $address
	 * @return self
	 */
	public function setAddress($address) {
		$this->address = (string) $address;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getZip() {
		return $this->zip;
	}

	/**
	 * @param string $zip
	 * @return self
	 */
	public function setZip($zip) {
		$this->zip = (string) $zip;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getCountry() {
		return $this->country;
	}

	/**
	 * @param string $country
	 * @return self
	 */
	public function setCountry($country) {
		$this->country = (string) $country;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getTin() {
		return $this->tin;
	}

	/**
	 * @param string $tin
	 * @throws Exception
	 * @return self
	 */
	public function setTin($tin) {
		if (!is_numeric($tin)) {
			throw new Exception(sprintf('Ic must be numeric, %s given.', gettype($tin)));
		}
		$this->tin = $tin;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getVaTin() {
		return $this->vaTin;
	}

	/**
	 * @param string $vaTin
	 * @return self
	 */
	public function setVaTin($vaTin) {
		$this->vaTin = (string) $vaTin;

		return $this;
	}

}
