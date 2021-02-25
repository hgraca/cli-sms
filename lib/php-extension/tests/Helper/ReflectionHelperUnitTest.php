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

namespace Acme\PhpExtension\Test\Helper;

use Acme\PhpExtension\Helper\ReflectionHelper;
use Acme\PhpExtension\Test\AbstractUnitTest;
use ReflectionException;

/**
 * @small
 */
final class ReflectionHelperUnitTest extends AbstractUnitTest
{
    /**
     * @throws ReflectionException
     *
     * @test
     */
    public function get_protected_property_from_object_class(): void
    {
        $value = 7;
        $object = new DummyClass($value);

        self::assertSame($value, ReflectionHelper::getProtectedProperty($object, 'var'));
    }

    /**
     * @throws ReflectionException
     *
     * @test
     */
    public function get_protected_property_from_object_parent_class(): void
    {
        $value = 7;
        $parentValue = 19;
        $object = new DummyClass($value, $parentValue);

        self::assertSame($parentValue, ReflectionHelper::getProtectedProperty($object, 'parentVar'));
    }

    /**
     * @throws ReflectionException
     *
     * @test
     */
    public function get_protected_property_throws_exception_if_not_found(): void
    {
        $object = new DummyClass();

        $this->expectException(ReflectionException::class);
        ReflectionHelper::getProtectedProperty($object, 'inexistentVar');
    }

    /**
     * @throws ReflectionException
     *
     * @test
     */
    public function set_protected_property(): void
    {
        $newValue = 'something new';
        $object = new DummyClass();
        $this->assertNotSame($newValue, $object->getTestProperty());

        ReflectionHelper::setProtectedProperty($object, 'testProperty', $newValue);
        $this->assertSame($newValue, $object->getTestProperty());
    }

    /**
     * @throws ReflectionException
     *
     * @test
     */
    public function set_protected_property_defined_in_parent_class(): void
    {
        $newValue = 'something new';
        $object = new DummyClass();
        $this->assertNotSame($newValue, $object->getParentTestProperty());

        ReflectionHelper::setProtectedProperty($object, 'parentTestProperty', $newValue);
        $this->assertSame($newValue, $object->getParentTestProperty());
    }

    /**
     * @test
     */
    public function set_protected_property_fails_when_cant_find_the_property(): void
    {
        $object = new DummyClass();
        $this->expectException(ReflectionException::class);
        $this->expectExceptionMessage('Property i_dont_exist does not exist');
        ReflectionHelper::setProtectedProperty($object, 'i_dont_exist', 'non existent');
    }

    /**
     * @throws ReflectionException
     *
     * @test
     */
    public function instantiate_without_constructor_does_not_use_the_constructor(): void
    {
        $object = ReflectionHelper::instantiateWithoutConstructor(DummyClass::class);
        $this->assertNull($object->getAnotherVar());
    }

    /**
     * @test
     *
     * @throws ReflectionException
     */
    public static function invokeProtectedMethod_works_with_protected_methods(): void
    {
        $var = 100;
        $dummyObject = new DummyClass($var);

        self::assertEquals($var, ReflectionHelper::invokeProtectedMethod($dummyObject, 'getVarProtected'));
    }

    /**
     * @test
     *
     * @throws ReflectionException
     */
    public static function invokeProtectedMethod_works_with_private_methods(): void
    {
        $var = 120;
        $dummyObject = new DummyClass($var);

        self::assertEquals($var, ReflectionHelper::invokeProtectedMethod($dummyObject, 'getVarPrivate'));
    }
}
