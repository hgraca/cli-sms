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
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * This is a version of the app tailored for testing use cases directly
 * We use it by instantiating it, replacing whatever services we need to replace
 * (so we don't hit the DB or 3rd party APIs) and dispatching commands or events into it.
 */
final class TestCore
{
    use ContainerAwareTestAppTrait;
    use KernelTestCaseTestAppTrait;

    /**
     * @var KernelInterface
     */
    private $kernel;

    public function __construct()
    {
        $this->kernel = $this->getkernel();
    }

    public function dispatchCommand(CommandInterface $command)
    {
        return $this->getCommandDispatcher()->dispatch($command);
    }

    public function dispatchEvent(EventInterface $event)
    {
        return $this->getEventDispatcher()->dispatch($event);
    }

    private function getContainer(): ContainerInterface
    {
        /** @var ContainerInterface $testContainer */
        $testContainer = $this->kernel->getContainer()->get('test.service_container');

        return $testContainer;
    }

    private function getCommandDispatcher(): CommandDispatcherInterface
    {
        throw new NotImplementedException();
    }

    private function getEventDispatcher(): EventDispatcherInterface
    {
        throw new NotImplementedException();
    }
}
