<?php

declare(strict_types=1);

namespace WebChemistry\Invoice\Data;

abstract class Subject {

	/** @var string */
	protected $name;

	/** @var string|null */
	protected $town;

	/** @var string|null */
	protected $address;

	/** @var string|null */
	protected $zip;

	/** @var string|null */
	protected $country;

	/** @var string|null */
	protected $tin;

	/** @var string|null */
	protected $vaTin;

	/**
	 * @param string $name
	 * @param string|null $town
	 * @param string|null $address
	 * @param string|null $zip
	 * @param string|null $country
	 * @param string|null $tin
	 * @param string|null $vaTin
	 */
	public function __construct(string $name, ?string $town, ?string $address, ?string $zip, ?string $country, ?string $tin = null, ?string $vaTin = null) {
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
	 * @return string|null
	 */
	public function getTown(): ?string {
		return $this->town;
	}

	/**
	 * @return string|null
	 */
	public function getAddress(): ?string {
		return $this->address;
	}

	/**
	 * @return string|null
	 */
	public function getZip(): ?string {
		return $this->zip;
	}

	/**
	 * @return string|null
	 */
	public function getCountry(): ?string {
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
