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

namespace Acme\App\Core\SharedKernel\Port\EventDispatcher;

/**
 * This interface has no functional value, because it does not require a set of methods, but it does have semantic
 * value: It will explicitly tell us that an object implementing this interface is an event, and it was designed with
 * the intention of being used with the event dispatcher. We will also know that any object not implementing this
 * interface is not designed with the intention of being dispatched by the event dispatcher.
 *
 * Furthermore, it allows us to strong type the argument of the event dispatcher, preventing us from making the mistake
 * of dispatching objects that are not intended to be dispatched by the event dispatcher.
 */
interface EventInterface
{
}
