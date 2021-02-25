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

use PHPUnit\Framework\AssertionFailedError;

trait SpyTrait
{
    /**
     * @var MethodCall[]
     */
    private $callList = [];

    private function addMethodCall(string $methodName, MethodArgumentCollection $arguments): void
    {
        $this->callList[] = new MethodCall($methodName, $arguments);
    }

    public function getMethodCall(string $methodName): MethodCall
    {
        foreach ($this->callList as $methodCall) {
            if ($methodCall->getMethodName() === $methodName) {
                return $methodCall;
            }
        }

        throw new AssertionFailedError("Couldn't find a method call for a method with name '$methodName'.");
    }
}
