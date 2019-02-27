<?php declare(strict_types = 1);

namespace Contributte\Invoice\DI;

use Contributte\Invoice\Data\Company;
use Contributte\Invoice\Formatter;
use Contributte\Invoice\IFormatter;
use Contributte\Invoice\Invoice;
use Contributte\Invoice\ITranslator;
use Contributte\Invoice\Renderers\IRenderer;
use Contributte\Invoice\Renderers\PDFRenderer;
use Contributte\Invoice\Templates\DefaultTemplate;
use Contributte\Invoice\Templates\ITemplate;
use Contributte\Invoice\Translator;
use Nette\DI\CompilerExtension;

class InvoiceExtension extends CompilerExtension
{

	/** @var mixed[] */
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
	];

	public function loadConfiguration(): void
	{
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
	}

}
