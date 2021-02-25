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

namespace Acme\App\Test\Framework;

use Acme\App\Test\Framework\TestAppTrait\ContainerAwareTestAppTrait;
use Acme\App\Test\Framework\TestAppTrait\KernelTestCaseTestAppTrait;
use Acme\App\Test\Framework\TestAppTrait\OrmTestAppTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * This is a version of the container tailored for testing specific services, configured as they are in the container.
 * We use it  by instantiating it, replacing the services we need to replace
 * (so we don't hit the DB or 3rd party APIs) and getting whatever service we want to test.
 */
final class TestContainer
{
    use ContainerAwareTestAppTrait { getService as public; }
    use KernelTestCaseTestAppTrait;
    use OrmTestAppTrait;

    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct()
    {
        $this->container = $this->getKernel()->getContainer()->get('test.service_container');
    }

    private function getContainer(): ContainerInterface
    {
        return $this->container;
    }
}
