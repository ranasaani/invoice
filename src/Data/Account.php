<?php

declare(strict_types=1);

namespace WebChemistry\Invoice\Data;

class Account {

	/** @var string */
	private $accountNumber;

	/** @var string|null */
	private $iBan;

	/** @var string|null */
	private $swift;

	/**
	 * @param string $accountNumber
	 * @param string|null $iBan
	 * @param string|null $swift
	 */
	public function __construct(string $accountNumber, ?string $iBan = null, ?string $swift = null) {
		$this->accountNumber = $accountNumber;
		$this->iBan = $iBan;
		$this->swift = $swift;
	}

	/////////////////////////////////////////////////////////////////

	/**
	 * @return string
	 */
	public function getAccountNumber(): string {
		return $this->accountNumber;
	}

	/**
	 * @return null|string
	 */
	public function getIBan(): ?string {
		return $this->iBan;
	}

	/**
	 * @return null|string
	 */
	public function getSwift(): ?string {
		return $this->swift;
	}

}
