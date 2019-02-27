<?php declare(strict_types = 1);

namespace Contributte\Invoice\Responses;

use Nette\Application\IResponse;
use Nette\Http\IRequest;
use Nette\Http\IResponse as NetteIResponse;

final class PdfResponse implements IResponse
{

	/** @var string */
	private $content;

	public function __construct(string $content)
	{
		$this->content = $content;
	}

	public function send(IRequest $httpRequest, NetteIResponse $httpResponse): void
	{
		$httpResponse->setContentType('application/pdf', 'utf-8');
		echo $this->content;
	}

}
