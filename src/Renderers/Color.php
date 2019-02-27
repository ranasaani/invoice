<?php declare(strict_types = 1);

namespace Contributte\Invoice\Renderers;

use Nette\SmartObject;

class Color
{

	use SmartObject;

	/** @var int */
	private $red;

	/** @var int */
	private $green;

	/** @var int */
	private $blue;

	public function __construct(int $red, int $green, int $blue)
	{
		$this->red = $red;
		$this->green = $green;
		$this->blue = $blue;
	}

	public function getRed(): int
	{
		return $this->red;
	}

	public function getGreen(): int
	{
		return $this->green;
	}

	public function getBlue(): int
	{
		return $this->blue;
	}

	protected function adjustColor(int $dimension): int
	{
		return max(0, min(255, $dimension));
	}

	protected function lightenDarken(int $percentage): Color
	{
		$percentage = round($percentage / 100, 2);

		return new Color(
			$this->adjustColor((int) ($this->red - ($this->red * $percentage))),
			$this->adjustColor((int) ($this->green - ($this->green * $percentage))),
			$this->adjustColor((int) ($this->blue - ($this->blue * $percentage)))
		);
	}

	public function lighten(int $percentage): Color
	{
		$percentage = max(0, min(100, $percentage));

		return $this->lightenDarken(-$percentage);
	}

	public function darken(int $percentage): Color
	{
		$percentage = max(0, min(100, $percentage));

		return $this->lightenDarken($percentage);
	}

	public static function black(): Color
	{
		return new Color(0, 0, 0);
	}

	public static function white(): Color
	{
		return new Color(255, 255, 255);
	}

}
