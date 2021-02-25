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

namespace Acme\App\Core\Port\Persistence;

interface TransactionServiceInterface
{
    public function startTransaction(): void;

    /**
     * This is is when the ORM writes all staged changes, to the DB
     *      so we should do this only once in a request, and only if the use case command was successful.
     */
    public function finishTransaction(): void;

    public function rollbackTransaction(): void;
}
