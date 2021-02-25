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

namespace Acme\App\Infrastructure\Http\Symfony;

use Acme\PhpExtension\Helper\JsonHelper;
use Acme\PhpExtension\Http\ContentTypeHttpHeader;
use Acme\PhpExtension\Http\HttpMethod;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerArgumentsEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final class ParseRequestDataListener implements EventSubscriberInterface
{
    public function parseJsonPostRequestData(ControllerArgumentsEvent $event): void
    {
        // We only want to parse the json data if it's a POST request.
        if ($event->getRequest()->getMethod() !== HttpMethod::POST) {
            return;
        }

        $arguments = $event->getArguments();

        /** @var mixed $argument */
        foreach ($arguments as &$argument) {
            if ($argument instanceof ServerRequestInterface && $this->isJsonRequest($argument)) {
                $argument = $argument->withParsedBody(JsonHelper::decode((string) $argument->getBody()));
            }
        }

        $event->setArguments($arguments);
    }

    private function isJsonRequest(ServerRequestInterface $request): bool
    {
        return $request->hasHeader(ContentTypeHttpHeader::NAME)
            && mb_strpos($request->getHeaderLine(ContentTypeHttpHeader::NAME), ContentTypeHttpHeader::VALUE_APPLICATION_JSON) !== false;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::CONTROLLER_ARGUMENTS => 'parseJsonPostRequestData',
        ];
    }
}
