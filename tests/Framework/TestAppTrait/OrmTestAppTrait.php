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

use Doctrine\ORM\EntityManagerInterface;

trait OrmTestAppTrait
{
    abstract public function getService(string $serviceId): object;

    public function saveEntities(object ...$entityList): void
    {
        foreach ($entityList as $entity) {
            $this->getEntityManager()->persist($entity);
        }
        $this->getEntityManager()->flush();
    }

    private function getEntityManager(): EntityManagerInterface
    {
        return $this->getService('doctrine')->getManager();
    }
}
