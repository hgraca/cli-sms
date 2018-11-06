<?php

declare(strict_types=1);

/*
 * This file is part of the CLI SMS application,
 * which is created on top of the Explicit Architecture POC,
 * which is created on top of the Symfony Demo application.
 *
 * This project is used for on-boarding of new developers into Werkspot dev teams.
 *
 * (c) Werkspot
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Acme\PhpExtension\Helper;

use Acme\PhpExtension\AbstractStaticClass;
use ReflectionClass;
use ReflectionException;
use ReflectionProperty;

final class ReflectionHelper extends AbstractStaticClass
{
    /**
     * @throws ReflectionException
     */
    public static function getProtectedProperty($object, string $propertyName)
    {
        $class = new ReflectionClass(\get_class($object));

        $property = static::getReflectionProperty($class, $propertyName);
        $property->setAccessible(true);

        return $property->getValue($object);
    }

    /**
     * @throws ReflectionException
     */
    public static function setProtectedProperty($object, string $propertyName, $value): void
    {
        $class = new ReflectionClass(\get_class($object));

        $property = static::getReflectionProperty($class, $propertyName);
        $property->setAccessible(true);
        $property->setValue($object, $value);
    }

    /**
     * @throws ReflectionException
     *
     * @return mixed
     */
    public static function instantiateWithoutConstructor(string $classFqcn)
    {
        $class = new ReflectionClass($classFqcn);

        return $class->newInstanceWithoutConstructor();
    }

    /**
     * @throws ReflectionException
     *
     * @return mixed
     */
    public static function invokeProtectedMethod($object, string $methodName, array $arguments = [])
    {
        $class = new ReflectionClass(\get_class($object));
        $method = $class->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $arguments);
    }

    /**
     * @throws ReflectionException
     */
    private static function getReflectionProperty(ReflectionClass $class, string $propertyName): ReflectionProperty
    {
        try {
            return $class->getProperty($propertyName);
        } catch (ReflectionException $e) {
            $parentClass = $class->getParentClass();
            if ($parentClass === false) {
                throw $e;
            }

            return static::getReflectionProperty($parentClass, $propertyName);
        }
    }
}
