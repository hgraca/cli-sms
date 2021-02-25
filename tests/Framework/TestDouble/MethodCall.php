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

namespace Acme\App\Test\Framework\TestDouble;

final class MethodCall
{
    /**
     * @var string
     */
    private $methodName;

    /**
     * @var MethodArgumentCollection
     */
    private $argumentCollection;

    public function __construct(string $methodName, MethodArgumentCollection $argumentCollection)
    {
        $this->methodName = $methodName;
        $this->argumentCollection = $argumentCollection;
    }

    public function getMethodName(): string
    {
        return $this->methodName;
    }

    /**
     * @return mixed
     */
    public function getArgument(string $parameterName)
    {
        return $this->argumentCollection->getArgument($parameterName)->getArgument();
    }
}
