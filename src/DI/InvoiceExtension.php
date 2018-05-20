<?php

declare(strict_types=1);

namespace WebChemistry\Invoice\DI;

use Nette\DI\CompilerExtension;
use WebChemistry\Invoice\Components\IPaginatorFactory;
use WebChemistry\Invoice\Components\PaginatorFactory;
use WebChemistry\Invoice\Data\Company;
use WebChemistry\Invoice\Data\Template;
use WebChemistry\Invoice\Formatter;
use WebChemistry\Invoice\IFormatter;
use WebChemistry\Invoice\Invoice;
use WebChemistry\Invoice\InvoiceFactory;
use WebChemistry\Invoice\ITranslator;
use WebChemistry\Invoice\Renderers\IRenderer;
use WebChemistry\Invoice\Renderers\PDFRenderer;
use WebChemistry\Invoice\Templates\DefaultTemplate;
use WebChemistry\Invoice\Templates\ITemplate;
use WebChemistry\Invoice\Translator;

class InvoiceExtension extends CompilerExtension {

	/** @var array */
	public $defaults = [
		'company' => [
			'name' => null,
			'town' => null,
			'address' => null,
			'zip' => null,
			'country' => null,
			'tin' => null,
			'vaTin' => null,
			'hasTax' => false,
		],
		'lang' => 'en',
		'invoiceFactory' => false,
	];

	public function loadConfiguration() {
		$builder = $this->getContainerBuilder();
		$config = $this->validateConfig($this->defaults);

		$builder->addDefinition($this->prefix('company'))
			->setFactory(Company::class, array_values($config['company']));

		$builder->addDefinition($this->prefix('template'))
			->setFactory(DefaultTemplate::class)
			->setType(ITemplate::class);

		$builder->addDefinition($this->prefix('renderer'))
			->setFactory(PDFRenderer::class)
			->setType(IRenderer::class);

		$builder->addDefinition($this->prefix('translation'))
			->setType(ITranslator::class)
			->setFactory(Translator::class, [$config['lang']]);

		$builder->addDefinition($this->prefix('formatter'))
			->setType(IFormatter::class)
			->setFactory(Formatter::class, [$config['lang']]);

		$builder->addDefinition($this->prefix('invoice'))
			->setFactory(Invoice::class);

		if ($config['invoiceFactory']) {
			$builder->addDefinition($this->prefix('invoiceFactory'))
				->setFactory(InvoiceFactory::class);
		}
	}

}
