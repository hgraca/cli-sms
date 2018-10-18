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

namespace Acme\App\Presentation\Console\Component\User;

use Acme\App\Core\Component\User\Application\Service\UserService;
use Acme\App\Core\Component\User\Application\Validation\UserValidationService;
use Acme\App\Core\Port\Validation\PhoneNumber\PhoneNumberException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Stopwatch\Stopwatch;

/**
 * A console command that creates users and stores them in the database.
 *
 * To use this command, open a terminal window, enter into your project
 * directory and execute the following:
 *
 *     $ php bin/console app:add-user
 *
 * To output detailed information, increase the command verbosity:
 *
 *     $ php bin/console app:add-user -vv
 *
 * See https://symfony.com/doc/current/cookbook/console/console_command.html
 * For more advanced uses, commands can be defined as services too. See
 * https://symfony.com/doc/current/console/commands_as_services.html
 *
 * @author Javier Eguiluz <javier.eguiluz@gmail.com>
 * @author Yonel Ceruto <yonelceruto@gmail.com>
 */
class AddUserCommand extends Command
{
    private const NAME = 'app:user:add';

    private const STOP_WATCH_NAME = self::NAME;

    /**
     * To make your command lazily loaded, configure the $defaultName static property,
     * so it will be instantiated only when the command is actually called.
     *
     * @var string
     */
    protected static $defaultName = self::NAME;

    /**
     * @var SymfonyStyle
     */
    private $io;

    /**
     * @var UserValidationService
     */
    private $validator;

    /**
     * @var UserService
     */
    private $userService;

    public function __construct(UserValidationService $validator, UserService $userService)
    {
        parent::__construct();

        $this->validator = $validator;
        $this->userService = $userService;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        $this
            ->setDescription('Creates users and stores them in the database')
            ->setHelp($this->getCommandHelp())
            // commands can optionally define arguments and/or options (mandatory and optional)
            // see https://symfony.com/doc/current/components/console/console_arguments.html
            ->addArgument('username', InputArgument::OPTIONAL, 'The username of the new user')
            ->addArgument('password', InputArgument::OPTIONAL, 'The plain password of the new user')
            ->addArgument('email', InputArgument::OPTIONAL, 'The email of the new user')
            ->addArgument('mobile', InputArgument::OPTIONAL, 'The mobile number of the new user')
            ->addArgument('full-name', InputArgument::OPTIONAL, 'The full name of the new user')
            ->addOption('admin', null, InputOption::VALUE_NONE, 'If set, the user is created as an administrator');
    }

    /**
     * This optional method is the first one executed for a command after configure()
     * and is useful to initialize properties based on the input arguments and options.
     */
    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        // SymfonyStyle is an optional feature that Symfony provides so you can
        // apply a consistent look to the commands of your application.
        // See https://symfony.com/doc/current/console/style.html
        $this->io = new SymfonyStyle($input, $output);
    }

    /**
     * This method is executed after initialize() and before execute(). Its purpose
     * is to check if some of the options/arguments are missing and interactively
     * ask the user for those values.
     *
     * This method is completely optional. If you are developing an internal console
     * command, you probably should not implement this method because it requires
     * quite a lot of work. However, if the command is meant to be used by external
     * users, this method is a nice way to fall back and prevent errors.
     */
    protected function interact(InputInterface $input, OutputInterface $output): void
    {
        if (
            $input->getArgument('username') !== null
            && $input->getArgument('password') !== null
            && $input->getArgument('email') !== null
            && $input->getArgument('mobile') !== null
            && $input->getArgument('full-name') !== null
        ) {
            return;
        }

        $this->io->title('Add User Command Interactive Wizard');
        $this->io->text(
            [
                'If you prefer to not use this interactive wizard, provide the',
                'arguments required by this command as follows:',
                '',
                ' $ php bin/console app:add-user username password email@example.com',
                '',
                'Now we\'ll ask you for the value of all the missing command arguments.',
            ]
        );

        // Ask for the username if it's not defined
        $username = $input->getArgument('username');
        if ($username !== null) {
            $this->io->text(' > <info>Username</info>: ' . $username);
        } else {
            $username = $this->io->ask('Username', null, [$this->validator, 'validateUsername']);
            $input->setArgument('username', $username);
        }

        // Ask for the password if it's not defined
        $password = $input->getArgument('password');
        if ($password !== null) {
            $this->io->text(' > <info>Password</info>: ' . str_repeat('*', mb_strlen($password)));
        } else {
            $password = $this->io->askHidden(
                'Password (your type will be hidden)',
                [$this->validator, 'validatePassword']
            );
            $input->setArgument('password', $password);
        }

        // Ask for the email if it's not defined
        $email = $input->getArgument('email');
        if ($email !== null) {
            $this->io->text(' > <info>Email</info>: ' . $email);
        } else {
            $email = $this->io->ask('Email', null, [$this->validator, 'validateEmail']);
            $input->setArgument('email', $email);
        }

        // Ask for the mobile if it's not defined
        $mobile = $input->getArgument('mobile');
        if ($mobile !== null) {
            $this->io->text(' > <info>Mobile</info>: ' . $mobile);
        } else {
            $mobile = $this->io->ask('Mobile', null, [$this->validator, 'validateMobile']);
            $input->setArgument('mobile', $mobile);
        }

        // Ask for the full name if it's not defined
        $fullName = $input->getArgument('full-name');
        if ($fullName !== null) {
            $this->io->text(' > <info>Full Name</info>: ' . $fullName);
        } else {
            $fullName = $this->io->ask('Full Name', null, [$this->validator, 'validateFullName']);
            $input->setArgument('full-name', $fullName);
        }
    }

    /**
     * This method is executed after interact() and initialize(). It usually
     * contains the logic to execute to complete this command task.
     *
     * @throws PhoneNumberException
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $stopwatch = new Stopwatch();
        $stopwatch->start(self::STOP_WATCH_NAME);

        $user = $this->userService->createUser(
            (string) $input->getArgument('username'),
            (string) $input->getArgument('password'),
            (string) $input->getArgument('email'),
            (string) $input->getArgument('mobile'),
            (string) $input->getArgument('full-name'),
            (bool) $input->getOption('admin')
        );

        $this->io->success(
            sprintf(
                '%s was successfully created: %s (%s)',
                $user->isAdmin() ? 'Administrator user' : 'User',
                $user->getUsername(),
                $user->getEmail()
            )
        );

        $event = $stopwatch->stop(self::STOP_WATCH_NAME);
        if ($output->isVerbose()) {
            $this->io->comment(
                sprintf(
                    'New user database id: %d / Elapsed time: %.2f ms / Consumed memory: %.2f MB',
                    $user->getId(),
                    $event->getDuration(),
                    $event->getMemory() / (1024 ** 2)
                )
            );
        }
    }

    /**
     * The command help is usually included in the configure() method, but when
     * it's too long, it's better to define a separate method to maintain the
     * code readability.
     */
    private function getCommandHelp(): string
    {
        return <<<'HELP'
The <info>%command.name%</info> command creates new users and saves them in the database:

  <info>php %command.full_name%</info> <comment>username password email</comment>

By default the command creates regular users. To create administrator users,
add the <comment>--admin</comment> option:

  <info>php %command.full_name%</info> username password email mobile <comment>--admin</comment>

If you omit any of the three required arguments, the command will ask you to
provide the missing values:

  # command will ask you for the email
  <info>php %command.full_name%</info> <comment>username password</comment>

  # command will ask you for the email and password
  <info>php %command.full_name%</info> <comment>username</comment>

  # command will ask you for all arguments
  <info>php %command.full_name%</info>

HELP;
    }
}
