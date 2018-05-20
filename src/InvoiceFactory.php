<?php

declare(strict_types=1);

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
	public static function createAccount(string $accountNumber, ?string $iBan = NULL, ?string $swift = NULL): Account {
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
	public static function createCompany(string $name, string $town, string $address, string $zip, string $country, ?string $tin = NULL,
										 ?string $vaTin = NULL, bool $hasTax = FALSE): Company {
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
	public static function createCustomer(string $name, string $town, string $address, string $zip, string $country, ?string $tin = NULL,
										  ?string $vaTin = NULL): Customer {
		return new Customer($name, $town, $address, $zip, $country, $tin, $vaTin);
	}

	/**
	 * @param string|int $number
	 * @param \DateTime $dueDate
	 * @param Account $account
	 * @param PaymentInformation $payment
	 * @param \DateTime|NULL $created
	 * @param bool $hasPriceWithTax
	 * @return Order
	 */
	public static function createOrder($number, ?\DateTime $dueDate, ?Account $account, PaymentInformation $payment,
									   \DateTime $created = NULL, bool $hasPriceWithTax = FALSE): Order {
		return new Order($number, $dueDate, $account, $payment, $created, $hasPriceWithTax);
	}

	/**
	 * @param string $currency
	 * @param string|null $variableSymbol
	 * @param string|null $constantSymbol
	 * @param float|null $tax
	 * @return PaymentInformation
	 */
	public static function createPaymentInformation(string $currency, ?string $variableSymbol = NULL, ?string $constantSymbol = NULL, ?float $tax = NULL): PaymentInformation {
		return new PaymentInformation($currency, $variableSymbol, $constantSymbol, $tax);
	}

}
