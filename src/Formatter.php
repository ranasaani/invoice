<?php

declare(strict_types=1);

namespace WebChemistry\Invoice;

class Formatter implements IFormatter {

	const ENGLISH = 'en',
		CZECH = 'cs';

	/** @var array */
	private static $options = [
		'cs' => [
			'number' => [
				'dec' => ',',
				'sep' => ' '
			],
			'money' => '%money %currency',
			'date' => 'd.m.Y',
		],
		'en' => [
			'number' => [
				'dec' => NULL,
				'sep' => NULL
			],
			'money' => '%currency %money',
			'date' => 'd/m/Y',
		],
	];

	/** @var string */
	private $lang;

	public function __construct(string $lang = self::ENGLISH) {
		$this->lang = $lang;
	}

	public function formatNumber($float): string {
		return number_format($float, 2, self::$options[$this->lang]['number']['dec'], self::$options[$this->lang]['number']['sep']);
	}

	public function formatMoney($float, string $currency): string {
		return strtr(self::$options[$this->lang]['money'], ['%money' => $this->formatNumber($float), '%currency' => $currency]);
	}

	public function formatDate(\DateTime $date): string {
		return $date->format(self::$options[$this->lang]['date']);
	}

}
