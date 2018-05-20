<?php

declare(strict_types=1);

namespace WebChemistry\Invoice\Renderers;

class PDF extends \FPDF {

	public function __construct(string $orientation = 'P', string $unit = 'mm', string $size = 'A4') {
		$px = false;
		if ($unit === 'px') {
			$unit = 'pt';
			$px = true;
		}
		parent::__construct($orientation, $unit, $size);

		if ($px) {
			$this->k = 72/96;

			$this->wPt = $this->w*$this->k;
			$this->hPt = $this->h*$this->k;
		}
	}

	function SetFontPath($fontPath) {
		$this->fontpath = $fontPath;
	}

	function Polygon($points, $style = 'D') {
		//Draw a polygon
		if ($style == 'F') {
			$op = 'f';
		} else if ($style == 'FD' || $style == 'DF') {
			$op = 'b';
		} else {
			$op = 's';
		}

		$h = $this->h;
		$k = $this->k;

		$points_string = '';
		for ($i = 0; $i < count($points); $i += 2) {
			$points_string .= sprintf('%.2F %.2F', $points[$i] * $k, ($h - $points[$i + 1]) * $k);
			if ($i == 0) {
				$points_string .= ' m ';
			} else {
				$points_string .= ' l ';
			}
		}
		$this->_out($points_string . $op);
	}

}
