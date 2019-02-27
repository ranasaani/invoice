<?php declare(strict_types = 1);

namespace Contributte\Invoice;

use DateTime;

interface IFormatter
{

	/**
	 * @param mixed $number
	 */
	public function formatNumber($number): string;

	/**
	 * @param mixed $number
	 */
	public function formatMoney($number, string $currency): string;

	public function formatDate(DateTime $date): string;

}
