<?php

namespace WebChemistry\Invoice;

class InvoiceException extends \Exception {

	public static function wrongType($need, $given) {
		$given = is_object($given) ? get_class($given) : gettype($given);

		return new self(sprintf('%s expected, %s given.', $need, $given));
	}

}
