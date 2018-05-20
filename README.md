[![Build Status](https://travis-ci.org/WebChemistry/invoice.svg?branch=master)](https://travis-ci.org/WebChemistry/invoice)

# PHP Invoice

## Installation

php 7.1

```
composer require webchemistry/invoice
```

## Usage

### Company

```php
$company = WebChemistry\Invoice\InvoiceFactory::createCompany('John Doe', 'Los Angeles', 'Cavetown', '720 55', 'USA', '0123456789', 'CZ0123456789');
```

### Customer

```php
$customer = WebChemistry\Invoice\InvoiceFactory::createCustomer('John Doe', 'Los Angeles', 'Cavetown', '720 55', 'USA');
```

### Account

```php
$account = WebChemistry\Invoice\InvoiceFactory::createAccount('1111', 'CZ4808000000002353462015', 'GIGACZPX');
```

### Payment info

```php
$payment = WebChemistry\Invoice\InvoiceFactory::createPaymentInformation('Kč', '0123456789', '1234', 0.21);
```

### Order

```php
$order = WebChemistry\Invoice\InvoiceFactory::createOrder('20160001', new \DateTime('+ 14 days'), $account, $payment);
```

Adding items

```php
$order->addItem('Logitech G700s Rechargeable Gaming Mouse', 4, 1790);
```

### Customizing

```php
class CustomFormatter implements IFormatter {
	
	/**
	 * @param float $float
	 * @return string
	 */
	public function formatNumber($float) {}

	/**
	 * @param float $float
	 * @param string $currency
	 * @return string
	 */
	public function formatMoney($float, $currency) {}

	/**
	 * @param \DateTime $date
	 * @return string
	 */
	public function formatDate(\DateTime $date) {}
	
}
```

## Generating invoices

```php
$invoice = new \WebChemistry\Invoice\Invoice($company);

header('Content-Type: application/pdf');
echo $invoice->create($customer, $order);
```

## Generating preview

```php
header('Content-Type: application/pdf');
echo $invoice->generatePreview();
```

## Nette DI

```yaml
extensions:
	invoice: WebChemistry\Invoice\DI\InvoiceExtension

invoice:
	lang: en
	company:
		name:
		town:
		address:
		zip:
		country:
		## Optional
		tin:
		vaTin:
		isTax:
```

```php

class Component {

	public function __construct(WebChemistry\Invoice\Invoice $invoice) {
		// ...
	}
}

```

## Previews

First page:
![first page](http://i.imgbox.com/pwFByZ1L.jpg)

Second page:
![second page](http://i.imgbox.com/ebrwXldf.jpg)
