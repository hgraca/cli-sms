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

namespace Acme\PhpExtension\Helper;

final class JsonHelper
{
    public static function encode(array $data, int $flags = 0, int $depth = 512): string
    {
        return json_encode($data, $flags, $depth);
    }

    /**
     * We can either return an associative array or a SplObject based on the $assoc boolean
     *
     * @throws InvalidJsonException
     *
     * @return array|object
     */
    public static function decode(string $json, bool $assoc = true, int $options = 0)
    {
        /** @var array|object $decoded */
        $decoded = json_decode($json, $assoc, 512, $options);

        switch (json_last_error()) {
            case \JSON_ERROR_NONE:
                /* @var array|object $decoded */
                return $decoded;
            case \JSON_ERROR_DEPTH:
                throw new InvalidJsonException('Maximum stack depth exceeded');
            case \JSON_ERROR_STATE_MISMATCH:
                throw new InvalidJsonException('Underflow or the modes mismatch');
            case \JSON_ERROR_CTRL_CHAR:
                throw new InvalidJsonException('Unexpected control character found');
            case \JSON_ERROR_SYNTAX:
                throw new InvalidJsonException('Syntax error, malformed JSON');
            case \JSON_ERROR_UTF8:
                throw new InvalidJsonException('Malformed UTF-8 characters, possibly incorrectly encoded');
            default:
                throw new InvalidJsonException('Unknown error');
        }
    }
}
