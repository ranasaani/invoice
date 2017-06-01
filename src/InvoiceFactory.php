<?php

namespace WebChemistry\Invoice;

use WebChemistry\Invoice\Data\Account;
use WebChemistry\Invoice\Data\Company;
use WebChemistry\Invoice\Data\Customer;
use WebChemistry\Invoice\Data\Order;
use WebChemistry\Invoice\Data\PaymentInformation;
use WebChemistry\Invoice\Data\Template;

class InvoiceFactory {

	/**
	 * @param string $accountNumber
	 * @param string|null $iBan
	 * @param string|null $swift
	 * @return Account
	 */
	public function createAccount($accountNumber, $iBan = NULL, $swift = NULL) {
		return new Account($accountNumber, $iBan, $swift);
	}

	/**
	 * @param string $name
	 * @param string $town
	 * @param string $address
	 * @param string $zip
	 * @param string $country
	 * @param string|null $tin
	 * @param string|null $vaTin
	 * @param bool $hasTax
	 * @return Company
	 */
	public function createCompany($name, $town, $address, $zip, $country, $tin = NULL, $vaTin = NULL, $hasTax = FALSE) {
		return new Company($name, $town, $address, $zip, $country, $tin, $vaTin, $hasTax);
	}

	/**
	 * @param string $name
	 * @param string $town
	 * @param string $address
	 * @param string $zip
	 * @param string $country
	 * @param string|null $tin
	 * @param string|null $vaTin
	 * @return Customer
	 */
	public function createCustomer($name, $town, $address, $zip, $country, $tin = NULL, $vaTin = NULL) {
		return new Customer($name, $town, $address, $zip, $country, $tin, $vaTin);
	}

	/**
	 * @param string|int $number
	 * @param \DateTime $dueDate
	 * @param Account $account
	 * @param PaymentInformation $payment
	 * @param \DateTime|NULL $created
	 * @return Order
	 */
	public function createOrder($number, \DateTime $dueDate, Account $account, PaymentInformation $payment, \DateTime $created = NULL) {
		return new Order($number, $dueDate, $account, $payment, $created);
	}

	/**
	 * @param string $currency
	 * @param string|null $variableSymbol
	 * @param string|null $constantSymbol
	 * @param float|null $tax
	 * @return PaymentInformation
	 */
	public function createPaymentInformation($currency, $variableSymbol = NULL, $constantSymbol = NULL, $tax = NULL) {
		return new PaymentInformation($currency, $variableSymbol, $constantSymbol, $tax);
	}

	/**
	 * @return Template
	 */
	public function createTemplate() {
		return new Template();
	}

}
