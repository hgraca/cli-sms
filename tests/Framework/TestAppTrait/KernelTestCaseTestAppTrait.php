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

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpKernel\KernelInterface;

trait KernelTestCaseTestAppTrait
{
    private function getKernel(): KernelInterface
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

        return $kernelTestCase->getKernel();
    }
}
