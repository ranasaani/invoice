<?php

namespace WebChemistry\Invoice\DI;

use Nette\DI\CompilerExtension;

class InvoiceExtension extends CompilerExtension {

	/** @var array */
	public $defaults = [
		'company' => [],
	];

	public function loadConfiguration() {
		$builder = $this->getContainerBuilder();
		$config = $this->validateConfig($this->defaults);

		$cmp = $builder->addDefinition($this->prefix('company'))
			->setClass('WebChemistry\Invoice\Data\Company');

		foreach ($config['company'] as $name => $value) {
			$cmp->addSetup($name, [$value]);
		}
		$cmp->addSetup('check()');

		$builder->addDefinition($this->prefix('template'))
			->setClass('WebChemistry\Invoice\Data\Template');

		$builder->addDefinition($this->prefix('invoice'))
			->setClass('WebChemistry\Invoice\Invoice', [$this->prefix('@company'), $this->prefix('@template')]);
	}

}
