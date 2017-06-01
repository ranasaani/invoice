<?php

declare(strict_types=1);

namespace WebChemistry\Invoice;

interface IFormatter {

	/**
	 * @param float $float
	 * @return string
	 */
	public function formatNumber($float): string;

	/**
	 * @param float $float
	 * @param string $currency
	 * @return string
	 */
	public function formatMoney($float, string $currency): string;

	/**
	 * @param \DateTime $date
	 * @return string
	 */
	public function formatDate(\DateTime $date): string;

}
