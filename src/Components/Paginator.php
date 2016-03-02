<?php

namespace WebChemistry\Invoice\Components;

use WebChemistry\Invoice\Data\Item;

class Paginator {

	/** @var int */
	public static $maxItems = 9;

	/** @var Item[] */
	private $items;

	/** @var int */
	protected $currentPage;

	/**
	 * @param array $items
	 * @return self
	 */
	public function setItems(array $items) {
		$this->items = $items;

		return $this;
	}

	/**
	 * @return int
	 */
	public function getTotalPages() {
		return (int) ceil(count($this->items) / 9);
	}

	/**
	 * @return Item[]
	 */
	public function getItems() {
		$page = $this->currentPage - 1;

		return array_slice($this->items, $page * 9, $page * 9 + 9);
	}

	/**
	 * @return bool
	 */
	public function isLastPage() {
		return $this->currentPage === $this->getTotalPages();
	}

	/**
	 * @return int
	 */
	public function getCurrentPage() {
		return $this->currentPage;
	}

	/**
	 * @param int $currentPage
	 * @return Paginator
	 */
	public function setCurrentPage($currentPage) {
		$this->currentPage = (int) $currentPage;

		return $this;
	}

}
