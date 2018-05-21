<?php

declare(strict_types=1);

namespace WebChemistry\Invoice\Renderers;

use Nette\SmartObject;

class Settings {

	use SmartObject;

	const DEFAULT_FONT_SIZE = null;

	const BORDER_LEFT = 'L',
		BORDER_RIGHT = 'R',
		BORDER_TOP = 'T',
		BORDER_BOTTOM = 'B',
		NO_BORDER = 0,
		BORDER = 1;

	const ALIGN_LEFT = 'L',
		ALIGN_CENTER = 'C',
		ALIGN_RIGHT = 'R',
		ALIGN_JUSTIFY = 'J';

	const FILL = true,
		NO_FILL = false;

	const FONT_STYLE_NONE = '',
		FONT_STYLE_ITALIC = 'I',
		FONT_STYLE_BOLD = 'B',
		FONT_STYLE_BOLD_ITALIC = 'BI';

	// properties

	public $border = self::NO_BORDER;

	public $align = null;

	public $fill = self::NO_FILL;

	public $fontSize = self::DEFAULT_FONT_SIZE;

	public $fontStyle = self::FONT_STYLE_NONE;

	public $fontFamily = null;

	/** @var null|Color */
	public $drawColor = null;

	/** @var null|Color */
	public $fontColor = null;

	/** @var null|Color */
	public $fillColor = null;

	public function setFillDrawColor(?Color $color) {
		$this->drawColor = $this->fillColor = $color;
	}

}
