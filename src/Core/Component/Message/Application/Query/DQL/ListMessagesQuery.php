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

namespace Acme\App\Core\Component\Message\Application\Query\DQL;

use Acme\App\Core\Component\Message\Application\Query\ListMessagesQueryInterface;
use Acme\App\Core\Component\Message\Domain\Message\Message;
use Acme\App\Core\Port\Persistence\DQL\DqlQueryBuilderInterface;
use Acme\App\Core\Port\Persistence\QueryServiceRouterInterface;
use Acme\App\Core\Port\Persistence\ResultCollectionInterface;

final class ListMessagesQuery implements ListMessagesQueryInterface
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

    public function find(array $orderBy = ['sentAt' => 'ASC'], int $limit = null): ResultCollectionInterface
    {
        $this->dqlQueryBuilder->create(Message::class, 'Message')
            ->select(
                'Message.id AS id',
                'Message.recipient AS recipient',
                'Message.message AS message',
                'Message.sentAt AS sentAt'
            )
            ->useScalarHydration();

        foreach ($orderBy as $column => $order) {
            $this->dqlQueryBuilder->orderBy($column, $order);
        }

        if ($limit !== null) {
            $this->dqlQueryBuilder->setMaxResults($limit);
        }

        return $this->queryService->query($this->dqlQueryBuilder->build());
    }
}
