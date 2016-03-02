<?php

namespace WebChemistry\Invoice;

use Nette\Localization\ITranslator;

class Translator implements ITranslator {

	/** @var array */
	private $translations = [
		'cs' => [
			'subscriber' => 'Odběratel',
			'vat' => 'IČ',
			'vaTin' => 'DIČ',
			'date' => 'Datum vystavení',
			'invoice' => 'Faktura',
			'invoiceNumber' => 'Číslo faktury',
			'taxPay' => 'Plátce DPH',
			'notTax' => 'Neplátce DPH',
			'paymentData' => 'Platební údaje',
			'page' => 'Stránka',
			'from' => 'z',
			'totalPrice' => 'Celková částka',
			'item' => 'Položka',
			'count' => 'Počet',
			'pricePerItem' => 'Cena za ks',
			'total' => 'Celkem',
			'accountNumber' => 'Číslo účtu',
			'swift' => 'Swift',
			'iban' => 'Iban',
			'varSymbol' => 'Variabilní symbol',
			'constSymbol' => 'Konstant. symbol',
			'tax' => 'DPH',
			'subtotal' => 'Mezisoučet',
			'dueDate' => 'Datum splatnosti'
		]
	];

	/** @var string */
	private $lang = 'cs';

	/**
	 * @param string $message
	 * @param null $count
	 * @return string
	 */
	public function translate($message, $count = NULL) {
		return isset($this->translations[$this->lang][$message]) ? $this->translations[$this->lang][$message] : $message;
	}

	/**
	 * @param string $lang
	 * @return self
	 */
	public function setLang($lang) {
		$this->lang = $lang;

		return $this;
	}

}
