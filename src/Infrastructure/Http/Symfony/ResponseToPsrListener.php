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

use Psr\Http\Message\ResponseInterface;
use Symfony\Bridge\PsrHttpMessage\HttpFoundationFactoryInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final class ResponseToPsrListener implements EventSubscriberInterface
{
    /**
     * @var HttpFoundationFactoryInterface
     */
    private $httpFoundationFactory;

    public function __construct(HttpFoundationFactoryInterface $httpFoundationFactory)
    {
        $this->httpFoundationFactory = $httpFoundationFactory;
    }

    /**
     * Do the conversion if applicable and update the response of the event.
     */
    public function transformResponseToPsr(ViewEvent $event): void
    {
        $controllerResult = $event->getControllerResult();

        if (!$controllerResult instanceof ResponseInterface) {
            return;
        }

        $event->setResponse($this->httpFoundationFactory->createResponse($controllerResult));
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => 'transformResponseToPsr',
        ];
    }
}
