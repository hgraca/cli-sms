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

namespace Acme\App\Test\TestCase\Presentation\Console\Component\User;

use Acme\App\Core\Component\User\Application\Repository\InMemory\UserRepository;
use Acme\App\Core\Component\User\Application\Repository\UserRepositoryInterface;
use Acme\App\Presentation\Console\Component\User\AddUserCommand;
use Acme\App\Test\Framework\AbstractTestCase;
use Acme\App\Test\Framework\TestCliApp;
use Generator;

/**
 * @medium
 */
final class AddUserCliCommandTest extends AbstractTestCase
{
    /**
     * @var array
     */
    private $userData = [
        'username' => 'chuck_norris',
        'password' => 'foobar',
        'email' => 'chuck@norris.com',
        'mobile' => '+31631769219',
        'full-name' => 'Chuck Norris',
    ];

    /**
     * @var TestCliApp
     */
    private $cliApp;

    protected function setUp(): void
    {
        exec('stty 2>&1', $output, $exitcode);
        $isSttySupported = $exitcode === 0;

        $isWindows = '\\' === \DIRECTORY_SEPARATOR;

        if ($isWindows || !$isSttySupported) {
            $this->markTestSkipped('`stty` is required to test this command.');
        }

        $this->cliApp = new TestCliApp();
    }

    /**
     * @dataProvider isAdminDataProvider
     *
     * This test provides all the arguments required by the command, so the
     * command runs non-interactively and it won't ask for any argument.
     *
     * @test
     */
    public function create_user_non_interactive(bool $isAdmin): void
    {
        $args = $this->userData;
        if ($isAdmin) {
            $args['--admin'] = 1;
        }

        $userRepository = new UserRepository();
        $this->cliApp->setService(UserRepositoryInterface::class, $userRepository);

        $this->cliApp->run(AddUserCommand::NAME, $args);

        $this->assertUserCreated($isAdmin, $userRepository);
    }

    /**
     * @dataProvider isAdminDataProvider
     *
     * This test doesn't provide all the arguments required by the command, so
     * the command runs interactively and it will ask for the value of the missing
     * arguments.
     * See https://symfony.com/doc/current/components/console/helpers/questionhelper.html#testing-a-command-that-expects-input
     *
     * @test
     */
    public function create_user_interactive(bool $isAdmin): void
    {
        $userRepository = new UserRepository();
        $this->cliApp->setService(UserRepositoryInterface::class, $userRepository);

        $this->cliApp->run(
            AddUserCommand::NAME,
            $isAdmin
                ? ['--admin' => 1]
                : [],   // these are the arguments (only 1 is passed, the rest are missing)
            array_values($this->userData)       // these are the responses given to the questions asked
        );

        $this->assertUserCreated($isAdmin, $userRepository);
    }

    /**
     * This is used to execute the same test twice: first for normal userRepository
     * (isAdmin = false) and then for admin userRepository (isAdmin = true).
     */
    public function isAdminDataProvider(): Generator
    {
        yield [false];
        yield [true];
    }

    /**
     * This helper method checks that the user was correctly created and saved
     * in the database.
     */
    private function assertUserCreated(bool $isAdmin, UserRepositoryInterface $userRepository): void
    {
        $user = $userRepository->findOneByEmail($this->userData['email']);

        $this->assertSame($this->userData['full-name'], $user->getFullName());
        $this->assertSame($this->userData['username'], $user->getUsername());
        $this->assertSame($this->userData['email'], $user->getEmail());
        $this->assertSame($this->userData['mobile'], $user->getMobile());
        $this->assertTrue($this->cliApp->isPasswordValid($user, $this->userData['password']));
        $this->assertSame(
            $isAdmin
                ? ['ROLE_ADMIN']
                : ['ROLE_USER'],
            $user->getRoles()
        );
    }
}
