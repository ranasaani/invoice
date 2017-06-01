<?php

namespace WebChemistry\Invoice\Components;

use WebChemistry\Invoice\Data\Item;

interface IPaginator {

	/**
	 * @return int
	 */
	public function getTotalPages();

	/**
	 * @return Item[]
	 */
	public function getItems();

	/**
	 * @return bool
	 */
	public function isLastPage();

	/**
	 * @return int
	 */
	public function getCurrentPage();

	/**
	 * @return bool
	 */
	public function hasNextPage();

}
