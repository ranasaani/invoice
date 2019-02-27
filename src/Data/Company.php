<?php declare(strict_types = 1);

namespace Contributte\Invoice\Data;

class Company extends Subject
{

	/** @var bool */
	protected $hasTax;

	public function __construct(string $name, string $town, string $address, string $zip, string $country, ?string $tin = null, ?string $vaTin = null, bool $hasTax = false)
	{
		parent::__construct($name, $town, $address, $zip, $country, $tin, $vaTin);
		$this->hasTax = $hasTax;
	}

	public function hasTax(): bool
	{
		return $this->hasTax;
	}

}
