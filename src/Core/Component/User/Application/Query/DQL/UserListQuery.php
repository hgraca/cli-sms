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

namespace Acme\App\Core\Component\User\Application\Query\DQL;

use Acme\App\Core\Component\User\Application\Query\UserListQueryInterface;
use Acme\App\Core\Component\User\Domain\User\User;
use Acme\App\Core\Port\Persistence\DQL\DqlQueryBuilderInterface;
use Acme\App\Core\Port\Persistence\QueryServiceRouterInterface;
use Acme\App\Core\Port\Persistence\ResultCollectionInterface;

final class UserListQuery implements UserListQueryInterface
{
    /**
     * @var DqlQueryBuilderInterface
     */
    private $dqlQueryBuilder;

    /**
     * @var QueryServiceRouterInterface
     */
    private $queryService;

    public function __construct(
        DqlQueryBuilderInterface $dqlQueryBuilder,
        QueryServiceRouterInterface $queryService
    ) {
        $this->dqlQueryBuilder = $dqlQueryBuilder;
        $this->queryService = $queryService;
    }

    public function execute(array $orderBy = ['id' => 'DESC'], int $limit = null): ResultCollectionInterface
    {
        $this->dqlQueryBuilder->create(User::class, 'User')
            ->select(
                'User.id AS id',
                'User.fullName As fullName',
                'User.username AS username',
                'User.email AS email'
            );

        foreach ($orderBy as $column => $order) {
            $this->dqlQueryBuilder->orderBy($column, $order);
        }

        if ($limit !== null) {
            $this->dqlQueryBuilder->setMaxResults($limit);
        }

        return $this->queryService->query($this->dqlQueryBuilder->build());
    }
}
