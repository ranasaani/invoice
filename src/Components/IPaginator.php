<?php

declare(strict_types=1);

namespace WebChemistry\Invoice\Components;

use WebChemistry\Invoice\Data\Item;

interface IPaginator {

	/**
	 * @return int
	 */
	public function getTotalPages(): int;

	/**
	 * @return Item[]
	 */
	public function getItems(): array;

	/**
	 * @return bool
	 */
	public function isLastPage(): bool;

	/**
	 * @return int
	 */
	public function getCurrentPage(): int;

	/**
	 * @return bool
	 */
	public function hasNextPage(): bool;

}
