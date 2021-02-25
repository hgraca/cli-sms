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

interface QueryBuilderInterface
{
    /**
     * Use constants to define configuration options that rarely change instead
     * of specifying them in config.
     *
     * See https://symfony.com/doc/current/best_practices/configuration.html#constants-vs-configuration-options
     */
    public const DEFAULT_MAX_RESULTS = 30;

    public function build(): QueryInterface;
}
