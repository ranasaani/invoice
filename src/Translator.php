<?php

namespace WebChemistry\Invoice;

class Translator implements ITranslator {

	const ENGLISH = 'en',
		  CZECH = 'cs';

	/** @var array */
	private static $translations = [
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
	private $lang;

	/**
	 * @param string $lang
	 * @throws InvoiceException
	 */
	public function __construct($lang = self::ENGLISH) {
		$this->lang = $lang;
		if (!isset(self::$translations[$this->lang])) {
			throw new InvoiceException("Language $lang not exists.");
		}
	}

	/**
	 * @param string $message
	 * @return string
	 */
	public function translate($message) {
		return self::$translations[$this->lang][$message];
	}

}
