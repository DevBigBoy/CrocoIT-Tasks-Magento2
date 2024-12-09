<?php
declare(strict_types=1);

namespace Croco\Articles\Api;

use Croco\Articles\Model\Category;
use Croco\Articles\Model\ResourceModel\Category\Collection;

interface CategoryRepositoryInterface
{
    /**
     * Get list of categories.
     *
     * @return Collection
     */
    public function getList(): Collection;
}
