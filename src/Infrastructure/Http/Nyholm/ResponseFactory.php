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

namespace Acme\App\Infrastructure\Http\Nyholm;

use Acme\App\Core\Port\Http\ResponseFactoryInterface;
use Acme\PhpExtension\Helper\JsonHelper;
use Acme\PhpExtension\Http\ContentTypeHttpHeader;
use Acme\PhpExtension\Http\ResponseStatus;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

final class ResponseFactory implements ResponseFactoryInterface
{
    /**
     * @param string|resource|StreamInterface|null $body Response body
     */
    public function create(
        $body = null,
        int $status = ResponseStatus::HTTP_OK,
        array $headers = [],
        string $version = '1.1',
        string $reason = null
    ): ResponseInterface {
        return new Response($status, $headers, $body, $version, $reason);
    }

    public function createJsonResponse(
        array $body = [],
        int $status = ResponseStatus::HTTP_OK,
        array $headers = [],
        string $version = '1.1',
        string $reason = null
    ): ResponseInterface {
        return new Response(
            $status,
            array_merge($headers, [
                ContentTypeHttpHeader::NAME => ContentTypeHttpHeader::VALUE_APPLICATION_JSON,
            ]),
            JsonHelper::encode($body),
            $version,
            $reason
        );
    }
}
