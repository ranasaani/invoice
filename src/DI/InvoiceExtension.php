<?php

namespace WebChemistry\Invoice\DI;

use Nette\DI\CompilerExtension;
use WebChemistry\Invoice\Components\IPaginatorFactory;
use WebChemistry\Invoice\Components\PaginatorFactory;
use WebChemistry\Invoice\Data\Company;
use WebChemistry\Invoice\Data\Template;
use WebChemistry\Invoice\IFormatter;
use WebChemistry\Invoice\Invoice;
use WebChemistry\Invoice\ITranslator;
use WebChemistry\Invoice\Translator;

class InvoiceExtension extends CompilerExtension {

	/** @var array */
	public $defaults = [
		'company' => [
			'name' => NULL,
			'town' => NULL,
			'address' => NULL,
			'zip' => NULL,
			'country' => NULL,
			'tin' => NULL,
			'vaTin' => NULL,
		],
		'lang' => 'en'
	];

	public function loadConfiguration() {
		$builder = $this->getContainerBuilder();
		$config = $this->validateConfig($this->defaults);

		$builder->addDefinition($this->prefix('company'))
			->setClass(Company::class, array_values($config['company']));

		$builder->addDefinition($this->prefix('template'))
			->setClass(Template::class);

		$builder->addDefinition($this->prefix('paginatorFactory'))
			->setClass(IPaginatorFactory::class)
			->setFactory(PaginatorFactory::class);

		$builder->addDefinition($this->prefix('translation'))
			->setClass(ITranslator::class)
			->setFactory(Translator::class, [$config['lang']]);

		$builder->addDefinition($this->prefix('formatter'))
			->setClass(IFormatter::class)
			->setFactory(Translator::class, [$config['lang']]);

		$builder->addDefinition($this->prefix('invoice'))
			->setClass(Invoice::class);
	}

}
