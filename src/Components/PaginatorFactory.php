<?php

declare(strict_types=1);

namespace WebChemistry\Invoice\Components;

use WebChemistry\Invoice\Data\Item;

class PaginatorFactory implements IPaginatorFactory {

	/**
	 * @param Item[] $items
	 * @return IPaginator
	 */
	public function createPaginator(array $items): IPaginator {
		return new Paginator($items);
	}

}
