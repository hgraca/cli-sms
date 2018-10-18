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

namespace Acme\App\Presentation\Console\Component\Message;

use Acme\App\Core\Component\Message\Application\Query\ListMessagesQueryInterface;
use function array_keys;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Stopwatch\Stopwatch;

class ListMessageCommand extends Command
{
    private const NAME = 'app:message:list';

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
     * @var ListMessagesQueryInterface
     */
    private $listMessagesQuery;

    public function __construct(ListMessagesQueryInterface $listMessagesQuery)
    {
        parent::__construct();

        $this->listMessagesQuery = $listMessagesQuery;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        $this
            ->setDescription('List all messages sent.')
            ->setHelp($this->getCommandHelp());
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
     * This method is executed after interact() and initialize(). It usually
     * contains the logic to execute to complete this command task.
     *
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $stopwatch = new Stopwatch();
        $stopwatch->start(self::STOP_WATCH_NAME);

        $messageList = $this->listMessagesQuery->find()->toArray();

        $this->io->table(array_keys($messageList[0]), $messageList);

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

        return 0;
    }

    /**
     * The command help is usually included in the configure() method, but when
     * it's too long, it's better to define a separate method to maintain the
     * code readability.
     */
    private function getCommandHelp(): string
    {
        return <<<'HELP'
The <info>%command.name%</info> command lists all messages sent:

  <info>php %command.full_name%</info>
HELP;
    }
}
