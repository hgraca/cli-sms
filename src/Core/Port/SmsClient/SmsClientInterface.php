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

namespace Acme\App\Core\Port\SmsClient;

/**
 * @author Nicolae Nichifor
 * @author Herberto Graca <herberto.graca@gmail.com>
 */
interface SmsClientInterface
{
    public function sendSms(Sms $sms): void;
}
