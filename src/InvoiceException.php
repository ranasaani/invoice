<?php declare(strict_types = 1);

namespace Contributte\Invoice;

use Exception;

class InvoiceException extends Exception
{

	/**
	 * @param mixed $need
	 * @param mixed $given
	 * @return InvoiceException
	 */
	public static function wrongType($need, $given): self
	{
		$given = is_object($given) ? get_class($given) : gettype($given);

		return new self(sprintf('%s expected, %s given.', $need, $given));
	}

}
