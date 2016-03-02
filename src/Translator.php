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
		],
		'en' => [
			'subscriber' => 'Subscriber',
			'vat' => 'VAT number',
			'vaTin' => 'VATIN',
			'date' => 'Date',
			'invoice' => 'Invoice',
			'invoiceNumber' => 'Invoice number',
			'taxPay' => '',
			'notTax' => 'VAT unregistered',
			'paymentData' => 'Payment information',
			'page' => 'Page',
			'from' => '/',
			'totalPrice' => 'Total price',
			'item' => 'Item',
			'count' => 'Quantity',
			'pricePerItem' => 'Price per item',
			'total' => 'Total',
			'accountNumber' => 'Account number',
			'swift' => 'Swift',
			'iban' => 'Iban',
			'varSymbol' => 'Variable symbol',
			'constSymbol' => 'Constant symbol',
			'tax' => 'TAX',
			'subtotal' => 'Subtotal',
			'dueDate' => 'Due date'
		]
	];

	/** @var string */
	private $lang = 'en';

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
