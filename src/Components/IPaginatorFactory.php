<?php

namespace WebChemistry\Invoice\Components;

use WebChemistry\Invoice\Data\Item;

interface IPaginatorFactory {

	/**
	 * @param Item[] $items
	 * @return IPaginator
	 */
	public function createPaginator(array $items);

}
