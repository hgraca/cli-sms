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

use Acme\PhpExtension\Collection;
use InvalidArgumentException;

/**
 * @property MethodArgument[] $itemList
 */
final class MethodArgumentCollection extends Collection
{
    public function __construct(MethodArgument ...$methodArgumentList)
    {
        foreach ($methodArgumentList as $methodArgument) {
            $this->itemList[$methodArgument->getName()] = $methodArgument;
        }
    }

    public function getArgument(string $parameterName): MethodArgument
    {
        if (!isset($this->itemList[$parameterName])) {
            throw new InvalidArgumentException();
        }

        return $this->itemList[$parameterName];
    }
}
