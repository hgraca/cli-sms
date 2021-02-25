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

namespace Acme\App\Infrastructure\Persistence\Doctrine;

use Acme\App\Core\Port\Persistence\QueryInterface;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\Query;

final class DqlQuery implements QueryInterface
{
    /**
     * @var Query
     */
    private $filters;

    /**
     * @var int
     */
    private $hydrationMode = AbstractQuery::HYDRATE_OBJECT;

    public function __construct(array $filters)
    {
        $this->filters = $filters;
    }

    public function getFilters(): array
    {
        return $this->filters;
    }

    public function setHydrationMode(int $hydrationMode): void
    {
        $this->hydrationMode = $hydrationMode;
    }

    public function getHydrationMode(): int
    {
        return $this->hydrationMode;
    }
}
