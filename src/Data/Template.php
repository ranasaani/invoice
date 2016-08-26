<?php

namespace WebChemistry\Invoice\Data;

use WebChemistry\Invoice\InvoiceException;

class Template {

	/** @var string */
	protected $font;

	/** @var string */
	protected $fontBold;

	/** @var string */
	protected $iconFont;

	/** @var array */
	protected $baseColor = [255,255,255];

	/** @var array */
	protected $primaryColor = [6, 178, 194];

	/** @var array */
	protected $fontColor = [52, 52, 53];

	/** @var array */
	protected $colorOdd = [255, 255, 255];

	/** @var array */
	protected $colorEven = [241, 240, 240];

	/** @var string */
	protected $footer;

	/** @var string */
	protected $logo;

	public function __construct() {
		$this->setFont(__DIR__ . '/../../assets/OpenSans-Regular.ttf');
		$this->setFontBold(__DIR__ . '/../../assets/OpenSans-Semibold.ttf');
		$this->setIconFont(__DIR__ . '/../../assets/pe.ttf');
	}

	/**
	 * @return string
	 */
	public function getFont() {
		return $this->font;
	}

	/**
	 * @param string $font
	 * @throws InvoiceException
	 * @return self
	 */
	public function setFont($font) {
		if (!file_exists($font) || !is_file($font)) {
			throw new InvoiceException("File '$font' not exists.");
		}
		$this->font = $font;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getFontBold() {
		return $this->fontBold;
	}

	/**
	 * @param string $fontBold
	 * @throws InvoiceException
	 * @return self
	 */
	public function setFontBold($fontBold) {
		if (!file_exists($fontBold) || !is_file($fontBold)) {
			throw new InvoiceException("File '$fontBold' not exists.");
		}
		$this->fontBold = $fontBold;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getIconFont() {
		return $this->iconFont;
	}

	/**
	 * @param string $iconFont
	 * @throws InvoiceException
	 * @return self
	 */
	public function setIconFont($iconFont) {
		if (!file_exists($iconFont) || !is_file($iconFont)) {
			throw new InvoiceException("File '$iconFont' not exists.");
		}
		$this->iconFont = $iconFont;

		return $this;
	}

	/**
	 * @return array
	 */
	public function getBaseColor() {
		return $this->baseColor;
	}

	/**
	 * @param array $baseColor
	 * @throws
	 * @return self
	 */
	public function setBaseColor(array $baseColor) {
		if (count($baseColor) !== 3) {
			throw new InvoiceException('Color must have 3 items.');
		}
		$this->baseColor = $baseColor;

		return $this;
	}

	/**
	 * @return array
	 */
	public function getPrimaryColor() {
		return $this->primaryColor;
	}

	/**
	 * @param array $primaryColor
	 * @throws InvoiceException
	 * @return self
	 */
	public function setPrimaryColor($primaryColor) {
		if (count($primaryColor) !== 3) {
			throw new InvoiceException('Color must have 3 items.');
		}
		$this->primaryColor = $primaryColor;

		return $this;
	}

	/**
	 * @return array
	 */
	public function getFontColor() {
		return $this->fontColor;
	}

	/**
	 * @param array $fontColor
	 * @throws InvoiceException
	 * @return self
	 */
	public function setFontColor($fontColor) {
		if (count($fontColor) !== 3) {
			throw new InvoiceException('Color must have 3 items.');
		}
		$this->fontColor = $fontColor;

		return $this;
	}

	/**
	 * @return array
	 */
	public function getColorOdd() {
		return $this->colorOdd;
	}

	/**
	 * @param array $colorOdd
	 * @throws InvoiceException
	 * @return self
	 */
	public function setColorOdd($colorOdd) {
		if (count($colorOdd) !== 3) {
			throw new InvoiceException('Color must have 3 items.');
		}
		$this->colorOdd = $colorOdd;

		return $this;
	}

	/**
	 * @return array
	 */
	public function getColorEven() {
		return $this->colorEven;
	}

	/**
	 * @param array $colorEven
	 * @throws InvoiceException
	 * @return self
	 */
	public function setColorEven($colorEven) {
		if (count($colorEven) !== 3) {
			throw new InvoiceException('Color must have 3 items.');
		}
		$this->colorEven = $colorEven;

		return $this;
	}

	/**
	 * @param string $footer
	 */
	public function setFooter($footer) {
		$this->footer = $footer;
	}

	/**
	 * @return string
	 */
	public function getFooter() {
		return $this->footer;
	}

	/**
	 * @param string $logo
	 */
	public function setLogo($logo) {
		$this->logo = $logo;
	}

	/**
	 * @return string
	 */
	public function getLogo() {
		return $this->logo;
	}

}
