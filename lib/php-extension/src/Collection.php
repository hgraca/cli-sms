<?php

declare(strict_types=1);

/*
 * This file is part of the CLI SMS application,
 * which is created on top of the Explicit Architecture POC,
 * which is created on top of the Symfony Demo application.
 *
 * This project is used in a workshop to explain Architecture patterns.
 *
 * Most of it authored by Herberto Graca.
 */

namespace Acme\PhpExtension;

use ArrayIterator;
use function count;
use Countable;
use Iterator;
use IteratorAggregate;

class Collection implements Countable, IteratorAggregate
{
    /**
     * @var array
     */
    protected $itemList;

    public function __construct(array $itemList = [])
    {
        $this->itemList = $itemList;
    }

    public function getIterator(): Iterator
    {
        return new ArrayIterator($this->itemList);
    }

    public function count(): int
    {
        return count($this->itemList);
    }

    public function toArray(): array
    {
        return $this->itemList;
    }

    public function contains($item): bool
    {
        return in_array($item, $this->itemList, true);
    }
}
