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

namespace Acme\PhpExtension\Test;

use Acme\PhpExtension\ConstructableFromArrayInterface;
use Acme\PhpExtension\ConstructableFromArrayTrait;

final class DummyConstructableFromArray implements ConstructableFromArrayInterface
{
    use ConstructableFromArrayTrait;

    /**
     * @var mixed
     */
    private $prop1;

    /**
     * @var int
     */
    private $prop2 = 9;

    public function __construct($prop1, $prop2 = 0)
    {
        $this->prop1 = $prop1;
        $this->prop2 = $prop2;
    }

    /**
     * @return mixed
     */
    public function getProp1()
    {
        return $this->prop1;
    }

    public function getProp2(): int
    {
        return $this->prop2;
    }
}
