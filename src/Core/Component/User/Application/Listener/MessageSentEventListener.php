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

namespace Acme\App\Core\Component\User\Application\Listener;

use Acme\App\Core\Component\User\Application\Repository\UserRepositoryInterface;
use Acme\App\Core\Port\Persistence\Exception\EmptyQueryResultException;
use Acme\App\Core\Port\Persistence\Exception\NotUniqueQueryResultException;
use Acme\App\Core\SharedKernel\Component\Message\Application\Event\MessageSentEvent;

final class MessageSentEventListener
{
    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function incrementUserMessageCount(MessageSentEvent $messageSentEvent): void
    {
        try {
            $user = $this->userRepository->findOneByMobile($messageSentEvent->getRecipient());
            $user->incrementMessageCount();
        } catch (EmptyQueryResultException $e) {
            // silently ignore
        } catch (NotUniqueQueryResultException $e) {
            // silently ignore
        }
    }
}
