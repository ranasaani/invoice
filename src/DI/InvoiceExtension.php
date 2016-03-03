<?php

namespace WebChemistry\Invoice\DI;

use Nette\DI\CompilerExtension;

class InvoiceExtension extends CompilerExtension {

	/** @var array */
	public $defaults = [
		'company' => [
			'name' => NULL,
			'town' => NULL,
			'address' => NULL,
			'zip' => NULL,
			'country' => NULL
		],
		'lang' => 'en'
	];

	public function loadConfiguration() {
		$builder = $this->getContainerBuilder();
		$config = $this->validateConfig($this->defaults);

		$company = $config['company'];

		$cmp = $builder->addDefinition($this->prefix('company'))
			->setClass('WebChemistry\Invoice\Data\Company', [
				$company['name'], $company['town'], $company['address'], $company['zip'], $company['country']
			]);
		unset($company['name'], $company['town'], $company['address'], $company['zip'], $company['country']);

		foreach ($company as $name => $value) {
			$cmp->addSetup('set' . ucfirst($name), [$value]);
		}

		$builder->addDefinition($this->prefix('template'))
			->setClass('WebChemistry\Invoice\Data\Template');

		$builder->addDefinition($this->prefix('invoice'))
			->setClass('WebChemistry\Invoice\Invoice', [$this->prefix('@company'), $this->prefix('@template')])
			->addSetup('?->getTranslator()->setLang(?)', ['@self', $config['lang']]);
	}

}
