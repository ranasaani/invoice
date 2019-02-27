<?php

declare(strict_types=1);

namespace WebChemistry\Invoice;

interface ITranslator {

	public function translate(string $message): string;

}
