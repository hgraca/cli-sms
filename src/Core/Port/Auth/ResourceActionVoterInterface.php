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

namespace Acme\App\Core\Port\Auth;

use Acme\App\Core\Component\Blog\Domain\Post\Post;
use Acme\App\Core\SharedKernel\Component\User\Domain\User\UserId;

interface ResourceActionVoterInterface
{
    // Defining these constants is overkill for this simple application, but for real
    // applications, it's a recommended practice to avoid relying on "magic strings"
    public const SHOW = 'show';
    public const EDIT = 'edit';
    public const DELETE = 'delete';

    public function supports(string $attribute, $subject): bool;

    public function voteOnAttribute(string $attribute, Post $post, UserId $userId): bool;
}
