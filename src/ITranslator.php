<?php

declare(strict_types=1);

namespace WebChemistry\Invoice;

interface ITranslator {

	/**
	 * @param string $message
	 * @return string
	 */
	public function translate(string $message): string;

}
