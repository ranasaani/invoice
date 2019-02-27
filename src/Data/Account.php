<?php declare(strict_types = 1);

namespace Contributte\Invoice\Data;

use Nette\SmartObject;

class Account
{

	use SmartObject;

	/** @var string */
	private $accountNumber;

	/** @var string|null */
	private $iBan;

	/** @var string|null */
	private $swift;

	public function __construct(string $accountNumber, ?string $iBan = null, ?string $swift = null)
	{
		$this->accountNumber = $accountNumber;
		$this->iBan = $iBan;
		$this->swift = $swift;
	}

	public function getAccountNumber(): string
	{
		return $this->accountNumber;
	}

	public function getIBan(): ?string
	{
		return $this->iBan;
	}

	public function getSwift(): ?string
	{
		return $this->swift;
	}

}
