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

namespace Acme\App\Test\Framework\TestAppTrait;

use Acme\App\Core\Component\User\Domain\User\User;
use Acme\App\Infrastructure\Auth\Symfony\SecurityUser;
use Exception;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;

trait ContainerAwareTestAppTrait
{
    abstract public function getContainer(): ContainerInterface;

    public function isPasswordValid(User $user, string $password): bool
    {
        return $this->getService('security.password_encoder')->isPasswordValid(
            SecurityUser::fromUser($user),
            $password
        );
    }

    public function setService(string $serviceId, ?object $service): void
    {
        $this->getContainer()->set($serviceId, $service);
    }

    private function getService(string $serviceId): object
    {
        $service = $this->getContainer()->get($serviceId);

        if (is_object($service) === false) {
            throw new Exception("Could not find an object for service '$serviceId' in the container.\nExpected an object but got:\n" . print_r($service, true));
        }

        return $service;
    }

    private function hasService(string $serviceId): bool
    {
        return $this->getContainer()->has($serviceId);
    }

    /**
     * @param string $name The parameter name
     *
     * @throws InvalidArgumentException if the parameter is not defined
     *
     * @return array|bool|float|int|string|null The parameter value
     */
    private function getParameter(string $name)
    {
        return $this->getContainer()->getParameter($name);
    }

    private function hasParameter(string $name): bool
    {
        return $this->getContainer()->hasParameter($name);
    }

    /**
     * @param array|bool|float|int|string|null $value The parameter value
     */
    private function setParameter(string $name, $value): void
    {
        $this->getContainer()->setParameter($name, $value);
    }
}
