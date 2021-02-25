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

namespace Acme\App\Core\Port\TemplateEngine;

interface EmailTemplateViewModelInterface extends TemplateViewModelInterface
{
    // This is required by the base email template, so it always needs to be there
    public function getSubject(): string;
}
