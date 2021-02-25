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

use Acme\App\Core\Component\User\Domain\User\User;
use Acme\App\Test\Framework\TestAppTrait\ContainerAwareTestAppTrait;
use Acme\App\Test\Framework\TestAppTrait\OrmTestAppTrait;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Bundle\FrameworkBundle\Test\TestContainer as SymfonyTestContainer;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * This is a version of the app tailored for CLI tests.
 * We use it by instantiating it, replacing whatever services we need to replace
 * (so we don't hit the DB or 3rd party APIs) and issue a CLI command interactive or not.
 */
final class TestCliApp
{
    use ContainerAwareTestAppTrait;
    use OrmTestAppTrait;

    /**
     * @var Application
     */
    private $cliApp;

    public function __construct()
    {
        $kernelTestCase = new /**
         * @internal
         *
         * @small
         */ class() extends KernelTestCase {
            public function getKernel(): KernelInterface
            {
                $kernel = self::bootKernel();
                static::$kernel = null;
                static::$booted = false;

                return $kernel;
            }
        };

        $this->cliApp = new Application($kernelTestCase->getKernel());
    }

    /**
     * @param string $commandName e.g.: 'app:create-user'
     * @param array $arguments e.g: ['username' => 'Wouter', '--some-option' => 'option_value',]
     *
     * @return string The output to the console
     */
    public function run(string $commandName, array $arguments, array $inputs = []): string
    {
        $commandTester = new CommandTester($this->cliApp->find($commandName));
        $commandTester->setInputs($inputs);
        $commandTester->execute($arguments);

        return $commandTester->getDisplay();
    }

    private function getContainer(): ContainerInterface
    {
        /** @var SymfonyTestContainer $testContainer */
        $testContainer = $this->cliApp->getKernel()->getContainer()->get('test.service_container');

        return $testContainer;
    }
}
