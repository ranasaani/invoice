# PHP Invoice

## Installation

```
composer require webchemistry/invoice
```

## Usage

### Company data

```php
$company = new \WebChemistry\Invoice\Data\Company();

$company->setAddress('address');
$company->setCountry('country');
$company->setFooter('footer');
$company->setName('name');
$company->setTown('town');
$company->setZip('77777');
$company->setTin('1111');
$company->setVaTin('CZ1111');
$company->setLogo(__DIR__ . '/logo.png'); // Recommended height is 106px
$company->setIsTax(TRUE);
```

### Customer data

```php
$customer = new \WebChemistry\Invoice\Data\Customer();

$customer->setAddress('address');
$customer->setCountry('country');
$customer->setTin('2222');
$customer->setVaTin('CZ2222');
$customer->setTown('town');
$customer->setName('name');
$customer->setZip('77777');
```

### Payment data

```php
$payment->setAccountNumber('1111');
$payment->setConstantSymbol('2222');
$payment->setVariableSymbol('3333');
$payment->setCurrency('Kč');
$payment->setIBan('4444');
$payment->setSwift('5555');
$payment->setMaturityDate(new \DateTime('+ 7 days'));
$payment->setInvoiceNumber(20160001);
```

Adding items

```php
$item = new \WebChemistry\Invoice\Data\Item();
$item->setName('item');
$item->setCount(rand(1,3));
$item->setPrice(rand(999, 100000));

$payment->addItem($item);
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