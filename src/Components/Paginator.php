<?php

declare(strict_types=1);

namespace WebChemistry\Invoice\Components;

use WebChemistry\Invoice\Data\Item;

class Paginator {

	/** @var Item[] */
	private $items;

	/** @var int */
	protected $currentPage = 0;

	/** @var int */
	protected $totalPages;

	/** @var int */
	private $itemsPerPage;

	/**
	 * @param Item[] $items
	 * @param int $itemsPerPage
	 */
	public function __construct(array $items, int $itemsPerPage) {
		$this->items = $items;
		$this->totalPages = (int) ceil(count($this->items) / $itemsPerPage);
		$this->itemsPerPage = $itemsPerPage;
	}

	/**
	 * @return int
	 */
	public function getTotalPages(): int {
		return $this->totalPages;
	}

	/**
	 * @return Item[]
	 */
	public function getItems(): array {
		$page = $this->currentPage - 1;

		return array_slice($this->items, $page * $this->itemsPerPage, $page * $this->itemsPerPage + $this->itemsPerPage);
	}

	public function isFirstPage(): bool {
		return $this->currentPage === 1;
	}

	/**
	 * @return bool
	 */
	public function isLastPage(): bool {
		return $this->currentPage >= $this->getTotalPages();
	}

	/**
	 * @return int
	 */
	public function getCurrentPage(): int {
		return $this->currentPage;
	}

	/**
	 * @return bool
	 */
	public function nextPage(): bool {
		if ($this->isLastPage()) {
			return FALSE;
		}
		$this->currentPage++;

		return TRUE;
	}

}
