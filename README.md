# PHP Invoice

## Installation

```
composer require webchemistry/invoice
```

## Usage

### Company

```php
$factory = new WebChemistry\Invoice\InvoiceFactory();
$company = $factory->createCompany('John Doe', 'Los Angeles', 'Cavetown', '720 55', 'USA', '0123456789', 'CZ0123456789');
```

### Customer

```php
$factory = new WebChemistry\Invoice\InvoiceFactory();
$customer = $factory->createCustomer('John Doe', 'Los Angeles', 'Cavetown', '720 55', 'USA');
```

### Account

```php
$factory = new WebChemistry\Invoice\InvoiceFactory();
$account = $factory->createAccount('1111', 'CZ4808000000002353462015', 'GIGACZPX');
```

### Payment info

```php
$factory = new WebChemistry\Invoice\InvoiceFactory();
$payment = $factory->createPaymentInformation('Kč', '0123456789', '1234', 0.21);
```

### Order

```php
$factory = new WebChemistry\Invoice\InvoiceFactory();
$order = $factory->createOrder('20160001', new \DateTime('+ 14 days'), $account, $payment);
```

Adding items

```php
$order->addItem('Logitech G700s Rechargeable Gaming Mouse', 4, 1790);
```

### Customizing template

```php
$template = new WebChemistry\Invoice\Data\Template();

// ...
```

## Generating invoices

```php
$invoice = new \WebChemistry\Invoice\Invoice($company);

$images = $invoice->create($customer, $order);
foreach ($images as $page => $invoice) {
	$invoice->save(__DIR__ . "/invoice-$page.jpg");
}

// Show first page

header('Content-Type: image/jpeg');
echo $images[0]->encode();
```

## Generating preview

```php
header('Content-Type: image/jpeg');
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
