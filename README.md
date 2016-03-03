# PHP Invoice

## Installation

```
composer require webchemistry/invoice
```

## Usage

### Company data

```php
$company = new Company('John Doe', 'Los Angeles', 'Cavetown', '720 55', 'USA');

$company->setFooter('footer');
$company->setTin('1111');
$company->setVaTin('CZ1111');
$company->setLogo(__DIR__ . '/logo.png'); // Recommended height is 106px
$company->setIsTax(TRUE);
```

### Customer data

```php
$customer = new Customer('John Doe', 'Los Angeles', 'Cavetown', '720 55', 'USA');
$customer->setTin('08304431');
$customer->setVaTin('CZ08304431');
```

### Payment data

```php
$payment->setAccountNumber('1111');
$payment = new Payment('2353462013/0800', 'Kč', '20150004');
$payment->setIBan('CZ4808000000002353462013');
$payment->setSwift('GIGACZPX');
$payment->setDueDate(new \DateTime('+ 7 days'));
```

Adding items

```php
$payment->addItem(new Item('Logitech G700s Rechargeable Gaming Mouse', 4, 1790));
```

### Customizing template

```php
$template = new WebChemistry\Invoice\Data\Template();

// ...
```

## Generating invoices

```php
$invoice = new \WebChemistry\Invoice\Invoice($company);

$images = $invoice->create($customer, $payment);
foreach ($images as $page => $invoice) {
	$invoice->save(__DIR__ . "/invoice-$page.jpg");
}

// Show first page

header('Content-Type: image/jpeg');
echo $images[0]->encode();
```

## Memory optimization

```php
$invoice->setSave(function (Intervention\Image\Image $image, $page) {
	$invoice->save(__DIR__ . "/invoice-$page.jpg");
});
```

## Translations
At first visit [core](https://github.com/WebChemistry/Invoice/blob/master/src/Translator.php) component.

Supported languages: English (en), Czech (cs)

Change language:
```php
$invoce->getTranslator()->setLang('en');
```

Custom translator:
```php

class MyTranslator implements Nette\Localization\ITranslator {

	public function translate($message, $count = NULL) { // $count is unnecessary
		// ...
	}

}

$invoice->setTranslator(new MyTranslator());
```

or you can send pull-request with your translation to core component.

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
	company:
		name:
		town:
		address:
		zip:
		country:
		## Optional
		tin:
		vaTin:
		logo:
		footer:
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