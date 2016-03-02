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
```

## Previews

First page:
![first page](http://i.imgbox.com/aykrwnkq.jpg)

Second page:
![second page](http://i.imgbox.com/7fvkelxr.jpg)