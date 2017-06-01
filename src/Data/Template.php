<?php

declare(strict_types=1);

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

	/** @var string|null */
	protected $footer;

	/** @var string|null */
	protected $logo;

	public function __construct() {
		$this->setFont(__DIR__ . '/../../assets/OpenSans-Regular.ttf');
		$this->setFontBold(__DIR__ . '/../../assets/OpenSans-Semibold.ttf');
		$this->setIconFont(__DIR__ . '/../../assets/pe.ttf');
	}

	/**
	 * @return string
	 */
	public function getFont(): string {
		return $this->font;
	}

	/**
	 * @param string $font
	 * @throws InvoiceException
	 * @return self
	 */
	public function setFont(string $font): self {
		if (!file_exists($font) || !is_file($font)) {
			throw new InvoiceException("File '$font' not exists.");
		}
		$this->font = $font;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getFontBold(): string {
		return $this->fontBold;
	}

	/**
	 * @param string $fontBold
	 * @throws InvoiceException
	 * @return self
	 */
	public function setFontBold(string $fontBold): self {
		if (!file_exists($fontBold) || !is_file($fontBold)) {
			throw new InvoiceException("File '$fontBold' not exists.");
		}
		$this->fontBold = $fontBold;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getIconFont(): string {
		return $this->iconFont;
	}

	/**
	 * @param string $iconFont
	 * @throws InvoiceException
	 * @return self
	 */
	public function setIconFont(string $iconFont): self {
		if (!file_exists($iconFont) || !is_file($iconFont)) {
			throw new InvoiceException("File '$iconFont' not exists.");
		}
		$this->iconFont = $iconFont;

		return $this;
	}

	/**
	 * @return array
	 */
	public function getBaseColor(): array {
		return $this->baseColor;
	}

	/**
	 * @param array $baseColor
	 * @throws InvoiceException
	 * @return self
	 */
	public function setBaseColor(array $baseColor): self {
		if (count($baseColor) !== 3) {
			throw new InvoiceException('Color must have 3 items.');
		}
		$this->baseColor = $baseColor;

		return $this;
	}

	/**
	 * @return array
	 */
	public function getPrimaryColor(): array {
		return $this->primaryColor;
	}

	/**
	 * @param array $primaryColor
	 * @throws InvoiceException
	 * @return self
	 */
	public function setPrimaryColor(array $primaryColor): self {
		if (count($primaryColor) !== 3) {
			throw new InvoiceException('Color must have 3 items.');
		}
		$this->primaryColor = $primaryColor;

		return $this;
	}

	/**
	 * @return array
	 */
	public function getFontColor(): array {
		return $this->fontColor;
	}

	/**
	 * @param array $fontColor
	 * @throws InvoiceException
	 * @return self
	 */
	public function setFontColor(array $fontColor): self {
		if (count($fontColor) !== 3) {
			throw new InvoiceException('Color must have 3 items.');
		}
		$this->fontColor = $fontColor;

		return $this;
	}

	/**
	 * @return array
	 */
	public function getColorOdd(): array {
		return $this->colorOdd;
	}

	/**
	 * @param array $colorOdd
	 * @throws InvoiceException
	 * @return self
	 */
	public function setColorOdd(array $colorOdd): self {
		if (count($colorOdd) !== 3) {
			throw new InvoiceException('Color must have 3 items.');
		}
		$this->colorOdd = $colorOdd;

		return $this;
	}

	/**
	 * @return array
	 */
	public function getColorEven(): array {
		return $this->colorEven;
	}

	/**
	 * @param array $colorEven
	 * @throws InvoiceException
	 * @return self
	 */
	public function setColorEven(array $colorEven): self {
		if (count($colorEven) !== 3) {
			throw new InvoiceException('Color must have 3 items.');
		}
		$this->colorEven = $colorEven;

		return $this;
	}

	/**
	 * @param string|null $footer
	 */
	public function setFooter(?string $footer = NULL) {
		$this->footer = $footer;
	}

	/**
	 * @return string|null
	 */
	public function getFooter(): ?string {
		return $this->footer;
	}

	/**
	 * @param string|null $logo
	 */
	public function setLogo(?string $logo = NULL) {
		$this->logo = $logo;
	}

	/**
	 * @return string
	 */
	public function getLogo(): ?string {
		return $this->logo;
	}

}
