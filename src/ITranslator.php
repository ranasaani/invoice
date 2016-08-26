<?php

namespace WebChemistry\Invoice;

interface ITranslator {

	/**
	 * @param string $message
	 * @return string
	 */
	public function translate($message);

}
