<?php

namespace WebChemistry\Invoice\Components;

use WebChemistry\Invoice\Data\Item;

class PaginatorFactory implements IPaginatorFactory {

	/**
	 * @param Item[] $items
	 * @return Paginator
	 */
	public function createPaginator(array $items) {
		return new Paginator($items);
	}

}
