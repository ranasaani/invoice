<?php declare(strict_types = 1);

namespace Contributte\Invoice\Data;

use Nette\SmartObject;

abstract class Subject
{

	use SmartObject;

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

	public function __construct(string $name, ?string $town = null, ?string $address = null, ?string $zip = null, ?string $country = null, ?string $tin = null, ?string $vaTin = null)
	{
		$this->name = $name;
		$this->town = $town;
		$this->address = $address;
		$this->zip = $zip;
		$this->country = $country;
		$this->tin = $tin;
		$this->vaTin = $vaTin;
	}

	public function getName(): string
	{
		return $this->name;
	}

	public function getTown(): ?string
	{
		return $this->town;
	}

	public function getAddress(): ?string
	{
		return $this->address;
	}

	public function getZip(): ?string
	{
		return $this->zip;
	}

	public function getCountry(): ?string
	{
		return $this->country;
	}

	public function getTin(): ?string
	{
		return $this->tin;
	}

	public function getVaTin(): ?string
	{
		return $this->vaTin;
	}

}
