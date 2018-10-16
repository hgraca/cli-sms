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

namespace Acme\App\Infrastructure\Lock\Symfony;

use Acme\App\Core\Port\Lock\LockManagerInterface;
use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;

final class LockManagerSubscriber implements EventSubscriberInterface
{
    // We run this subscriber just after the transaction is closed, so we release all locks, so that they can be
    // reacquired when processing the events in the EventFlusherSubscriber, which runs after this subscriber.
    private const DEFAULT_PRIORITY = 20;

    /**
     * @var LockManagerInterface
     */
    private $lockManager;

    /**
     * @var int
     */
    private static $priority = self::DEFAULT_PRIORITY;

    public function __construct(
        LockManagerInterface $lockManager,
        int $lockManagerSubscriberPriority = self::DEFAULT_PRIORITY
    ) {
        $this->lockManager = $lockManager;
        self::$priority = $lockManagerSubscriberPriority;
    }

    /**
     * Return the subscribed events, their methods and possibly their priorities
     * (the higher the priority the earlier the method is called).
     *
     * @see http://symfony.com/doc/current/event_dispatcher.html#creating-an-event-subscriber
     */
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::RESPONSE => ['releaseAllLocks', self::$priority],
            ConsoleEvents::TERMINATE => ['releaseAllLocks', self::$priority],

            // In the case that both the Exception and Response events are triggered, we want to make sure the
            // transaction is rolled back before trying to commit it, so the priority is higher than for the response.
            KernelEvents::EXCEPTION => ['releaseAllLocks', self::$priority + 1],
            ConsoleEvents::ERROR => ['releaseAllLocks', self::$priority],
        ];
    }

    public function releaseAllLocks(): void
    {
        // We release all locks here, so that they can be reacquired when processing the events in the
        // EventFlusherSubscriber, which runs after this subscriber.
        $this->lockManager->releaseAll();
    }
}
