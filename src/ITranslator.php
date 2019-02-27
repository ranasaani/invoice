<?php declare(strict_types = 1);

namespace Contributte\Invoice;

interface ITranslator
{

	public function translate(string $message): string;

}
