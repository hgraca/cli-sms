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

namespace Acme\App\Infrastructure\Router\Symfony;

use Acme\App\Core\Port\Router\UrlGeneratorInterface;
use Acme\App\Core\Port\Router\UrlType;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface as SymfonyUrlGeneratorInterface;

final class UrlGeneratorService implements UrlGeneratorInterface
{
    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    public function __construct(SymfonyUrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    public function generateUrl(
        string $routeName,
        array $parameters = [],
        UrlType $urlType = null
    ): string {
        if (!$urlType) {
            $urlType = UrlType::absolutePath();
        }

        return $this->urlGenerator->generate($routeName, $parameters, $urlType->getValue());
    }
}
