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

namespace Acme\App\Test\Framework;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

abstract class AbstractConsoleTest extends AbstractIntegrationTest
{
    protected function execute(string $commandName, array $arguments = []): string
    {
        $application = new Application(self::$kernel);

        $commandTester = new CommandTester(
            $application->find($commandName)
        );

        $commandTester->execute(
            [
                'command' => $commandName,
            ] + $arguments,
            [
                'interactive' => false,
            ]
        );

        return $commandTester->getDisplay();
    }
}
