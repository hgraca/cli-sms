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

namespace Acme\App\Core\Port\SmsClient;

/**
 * This is just a DTO, it only has getters, theres no logic to test, so we ignore it for code coverage purposes.
 *
 * @codeCoverageIgnore
 *
 * @author Nicolae Nichifor
 * @author Herberto Graca <herberto.graca@gmail.com>
 */
final class Sms
{
    /**
     * @var string
     */
    private $content;

    /**
     * @var string
     */
    private $phoneNumber;

    public function __construct(string $content, string $phoneNumber)
    {
        $this->content = $content;
        $this->phoneNumber = $phoneNumber;
    }

    public function getPhoneNumber(): string
    {
        return $this->phoneNumber;
    }

    public function getContent(): string
    {
        return $this->content;
    }
}
