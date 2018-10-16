<?php

declare(strict_types=1);

/*
 * This file is part of the CLI SMS application,
 * which is created on top of the Explicit Architecture POC,
 * which is created on top of the Symfony Demo application.
 *
 * This project is used for on-boarding of new developers into Werkspot dev teams.
 *
 * (c) Werkspot
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Acme\App\Core\Port\Persistence;

/**
 * This is the query service that will be used throughout the application, it's the entry point for querying.
 * It will receive a query object and route it to one of the underlying query services,
 * which is designed to handle that specific type of query objects.
 */
interface QueryServiceRouterInterface
{
    public function query(QueryInterface $query): ResultCollectionInterface;
}
