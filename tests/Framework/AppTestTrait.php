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

use Acme\PhpExtension\DateTime\DateTimeGenerator;
use Acme\PhpExtension\Uuid\UuidGenerator;
use DOMDocument;

trait AppTestTrait
{
    /**
     * @after
     */
    public function resetDateTimeGenerator(): void
    {
        DateTimeGenerator::reset();
    }

    /**
     * @after
     */
    public function resetUuidGenerator(): void
    {
        UuidGenerator::reset();
    }

    public function assertValidHtml(string $html): void
    {
        $doc = new DOMDocument();

        if ($doc->loadHTML($html) === false) {
            self::fail('The provided string is not valid HTML.');
        }
    }
}
