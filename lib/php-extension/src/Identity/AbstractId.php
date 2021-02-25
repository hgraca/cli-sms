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

namespace Acme\PhpExtension\Identity;

use Acme\PhpExtension\EqualityInterface;
use Acme\PhpExtension\ScalarObjectInterface;
use JsonSerializable;

/**
 * Maybe we could avoid having this class and the inheritance tree here, but I find it nice because of the validation
 * it encapsulates, because of the global type hinting it provides, and also just as an example.
 */
abstract class AbstractId implements JsonSerializable, ScalarObjectInterface, EqualityInterface
{
    /**
     * @var mixed
     */
    protected $id;

    public function __construct($id)
    {
        $this->validate($id);

        $this->id = $id;
    }

    /**
     * This is an example of the Template pattern, where this method is defined (templated) and used here,
     * but implemented in a subclass.
     */
    abstract protected function isValid($value): bool;

    public function __toString(): string
    {
        return (string) $this->id;
    }

    public function jsonSerialize()
    {
        return $this->id;
    }

    /**
     * @param self $id
     */
    public function equals($id): bool
    {
        return static::class === \get_class($id)
            && $this->id === $id->id;
    }

    protected function validate($id): void
    {
        if (!$this->isValid($id)) {
            throw new InvalidIdException($id);
        }
    }
}
