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

use Acme\App\Infrastructure\Http\Nyholm\ResponseFactory;
use Acme\App\Test\Framework\TestAppTrait\ContainerAwareTestAppTrait;
use Acme\App\Test\Framework\TestAppTrait\OrmTestAppTrait;
use Acme\PhpExtension\Http\HttpMethod;
use Psr\Http\Message\ResponseInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\TestContainer as SymfonyTestContainer;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * This is a version of the app tailored for functional tests, emulating HTTP Requests/Responses.
 * We use it by instantiating it, replacing whatever services we need to replace
 * (so we don't hit the DB or 3rd party APIs) and send requests to the application.
 */
final class TestWebApp
{
    use ContainerAwareTestAppTrait;
    use OrmTestAppTrait;

    /**
     * @var KernelBrowser
     */
    private $client;

    public function __construct()
    {
        $webTestCase = new /**
         * @small
         *
         * @internal
         */ class() extends WebTestCase {
            public function extractClient(): KernelBrowser
            {
                $client = static::createClient();
                static::$kernel = null;
                static::$booted = false;

                return $client;
            }
        };

        $this->client = $webTestCase->extractClient();
        $this->client->catchExceptions(false);
    }

    /**
     * @param array|object|null $requestData the request data
     */
    public function sendGetRequest(string $requestUri, $requestData = []): ResponseInterface
    {
        $this->client->request(HttpMethod::GET, $requestUri, $requestData);

        return $this->transformSymfonyResponseToPsr($this->client->getResponse());
    }

    /**
     * @param array|object|null $requestData the request data
     */
    public function sendPostRequest(string $requestUri, $requestData = null): ResponseInterface
    {
        $this->client->request(HttpMethod::POST, $requestUri, $requestData);

        return $this->transformSymfonyResponseToPsr($this->client->getResponse());
    }

    private function getContainer(): ContainerInterface
    {
        /** @var SymfonyTestContainer $testContainer */
        $testContainer = $this->client->getContainer()->get('test.service_container');

        return $testContainer;
    }

    private function transformSymfonyResponseToPsr(Response $symfonyResponse): ResponseInterface
    {
        $response = (new ResponseFactory())->create(
            $symfonyResponse->getContent(),
            $symfonyResponse->getStatusCode(),
            $symfonyResponse->headers->allPreserveCase(),
            $symfonyResponse->getProtocolVersion()
        );
        $response->getBody()->rewind();

        return $response;
    }
}
