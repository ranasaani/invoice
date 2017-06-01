<?php

declare(strict_types=1);

namespace WebChemistry\Invoice\Data;

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

	/** @var string|null */
	protected $tin;

	/** @var string|null */
	protected $vaTin;

	/**
	 * @param string $name
	 * @param string $town
	 * @param string $address
	 * @param string $zip
	 * @param string $country
	 * @param string|null $tin
	 * @param string|null $vaTin
	 */
	public function __construct(string $name, string $town, string $address, string $zip, string $country, ?string $tin = NULL, ?string $vaTin = NULL) {
		$this->name = $name;
		$this->town = $town;
		$this->address = $address;
		$this->zip = $zip;
		$this->country = $country;
		$this->tin = $tin;
		$this->vaTin = $vaTin;
	}

	/**
	 * @return string
	 */
	public function getName(): string {
		return $this->name;
	}

	/**
	 * @return string
	 */
	public function getTown(): string {
		return $this->town;
	}

	/**
	 * @return string
	 */
	public function getAddress(): string {
		return $this->address;
	}

	/**
	 * @return string
	 */
	public function getZip(): string {
		return $this->zip;
	}

	/**
	 * @return string
	 */
	public function getCountry(): string {
		return $this->country;
	}

	/**
	 * @return string|null
	 */
	public function getTin(): ?string {
		return $this->tin;
	}

	/**
	 * @return string|null
	 */
	public function getVaTin(): ?string {
		return $this->vaTin;
	}

}
