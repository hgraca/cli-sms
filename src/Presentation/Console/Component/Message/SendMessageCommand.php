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

namespace Acme\App\Presentation\Console\Component\Message;

use Acme\App\Core\Component\Message\Application\Service\MessageService;
use Acme\App\Core\Component\User\Application\Validation\UserValidationService;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Stopwatch\Stopwatch;

class SendMessageCommand extends Command
{
    private const NAME = 'app:message:send';

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
     * @var MessageService
     */
    private $messageService;

    public function __construct(
        UserValidationService $validator,
        MessageService $messageService
    ) {
        parent::__construct();

        $this->validator = $validator;
        $this->messageService = $messageService;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        $this
            ->setDescription('"Sends" a message.')
            ->setHelp($this->getCommandHelp())
            // commands can optionally define arguments and/or options (mandatory and optional)
            // see https://symfony.com/doc/current/components/console/console_arguments.html
            ->addArgument('phoneNumber', InputArgument::OPTIONAL, 'The phone number where to send the message')
            ->addArgument('message', InputArgument::OPTIONAL, 'The message to send');
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
            $input->getArgument('phoneNumber') !== null
            && $input->getArgument('message') !== null
        ) {
            return;
        }

        $this->io->title('Send message Command Interactive Wizard');
        $this->io->text(
            [
                'If you prefer to not use this interactive wizard, provide the',
                'arguments required by this command as follows:',
                '',
                ' $ php bin/console app:add-user phoneNumber "message"',
                '',
                'Now we\'ll ask you for the value of all the missing command arguments.',
            ]
        );

        // Ask for the phoneNumber if it's not defined
        $phoneNumber = $input->getArgument('phoneNumber');
        if ($phoneNumber !== null) {
            $this->io->text(' > <info>Phone Number</info>: ' . $phoneNumber);
        } else {
            $phoneNumber = $this->io->ask('Phone Number', null, [$this->validator, 'validateMobile']);
            $input->setArgument('phoneNumber', $phoneNumber);
        }

        // Ask for the message if it's not defined
        $message = $input->getArgument('message');
        if ($message !== null) {
            $this->io->text(' > <info>Message</info>: ' . $message);
        } else {
            $message = $this->io->ask('Full Name', null);
            $input->setArgument('message', $message);
        }
    }

    /**
     * This method is executed after interact() and initialize(). It usually
     * contains the logic to execute to complete this command task.
     *
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $stopwatch = new Stopwatch();
        $stopwatch->start(self::STOP_WATCH_NAME);

        $this->messageService->send(
            (string) $input->getArgument('phoneNumber'),
            (string) $input->getArgument('message')
        );

        $event = $stopwatch->stop(self::STOP_WATCH_NAME);
        if ($output->isVerbose()) {
            $this->io->comment(
                sprintf(
                    'Elapsed time: %.2f ms / Consumed memory: %.2f MB',
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
The <info>%command.name%</info> command "sends" a message and saves it in the database:

  <info>php %command.full_name%</info> <comment>phone_number message</comment>

If you omit any of the required arguments, the command will ask you to provide the missing values.
HELP;
    }
}
