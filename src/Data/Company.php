<?php

namespace WebChemistry\Invoice\Data;

class Company extends AbstractData {

	/** @var string */
	protected $logo;

	/** @var string */
	protected $footer;

	/** @var bool */
	protected $isTax = FALSE;

	/**
	 * @return boolean
	 */
	public function isTax() {
		return $this->isTax;
	}

	/**
	 * @param boolean $isTax
	 * @return self
	 */
	public function setIsTax($isTax) {
		$this->isTax = (bool) $isTax;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getLogo() {
		return $this->logo;
	}

	/**
	 * @param string $logo
	 * @return self
	 */
	public function setLogo($logo) {
		$this->logo = (string) $logo;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getFooter() {
		return $this->footer;
	}

	/**
	 * @param string $footer
	 * @return self
	 */
	public function setFooter($footer) {
		$this->footer = (string) $footer;

		return $this;
	}

}
