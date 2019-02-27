<?php declare(strict_types = 1);

namespace Contributte\Invoice\Renderers;

use Nette\SmartObject;

class Settings
{

	use SmartObject;

	public const DEFAULT_FONT_SIZE = null;

	public const BORDER_LEFT = 'L',
		BORDER_RIGHT = 'R',
		BORDER_TOP = 'T',
		BORDER_BOTTOM = 'B',
		NO_BORDER = 0,
		BORDER = 1;

	public const ALIGN_LEFT = 'L',
		ALIGN_CENTER = 'C',
		ALIGN_RIGHT = 'R',
		ALIGN_JUSTIFY = 'J';

	public const FILL = true,
		NO_FILL = false;

	public const FONT_STYLE_NONE = '',
		FONT_STYLE_ITALIC = 'I',
		FONT_STYLE_BOLD = 'B',
		FONT_STYLE_BOLD_ITALIC = 'BI';

	/** @var int|string */
	public $border = self::NO_BORDER;

	/** @var string|null */
	public $align = null;

	/** @var bool */
	public $fill = self::NO_FILL;

	/** @var int|null */
	public $fontSize = self::DEFAULT_FONT_SIZE;

	/** @var string */
	public $fontStyle = self::FONT_STYLE_NONE;

	/** @var string|null */
	public $fontFamily = null;

	/** @var Color|null */
	public $drawColor = null;

	/** @var Color|null */
	public $fontColor = null;

	/** @var Color|null */
	public $fillColor = null;

	/**
	 * @return static
	 */
	public function setFillDrawColor(?Color $color)
	{
		$this->drawColor = $this->fillColor = $color;

		return $this;
	}

}
