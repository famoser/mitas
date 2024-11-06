<?php

declare(strict_types=1);

/*
 * This file is part of the evoting.uzh.ch project.
 *
 * (c) Florian Moser <git@famoser.ch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Command;

use App\Entity\Era;
use App\Helper\DoctrineHelper;
use App\Services\Interfaces\EmailServiceInterface;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Command\LockableTrait;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RunPeriodicTasks extends Command
{
    use LockableTrait;

    private ManagerRegistry $doctrine;

    private EmailServiceInterface $emailService;

    private LoggerInterface $logger;
    private ManagerRegistry $registry;

    public function __construct(ManagerRegistry $doctrine, EmailServiceInterface $emailService, LoggerInterface $logger, ManagerRegistry $managerRegistry)
    {
        parent::__construct();

        $this->doctrine = $doctrine;
        $this->emailService = $emailService;
        $this->logger = $logger;
        $this->registry = $managerRegistry;
    }

    protected function configure(): void
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('app:run-periodic-tasks')
            // the short description shown while running "php bin/console list"
            ->setDescription('Runs period tasks, e.g. dispatching reminder emails.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $eras = $this->doctrine->getRepository(Era::class)->findBy(['reminderSentAt' => null]);
        foreach ($eras as $era) {
            if (!$era->getAnnouncedAt() || !$era->isDeadlineToday()) {
                continue;
            }

            $oneDayAgo = (new \DateTime())->sub(new \DateInterval('P1D'));
            $allSuccessful = true;
            foreach ($era->getEntries() as $entry) {
                if ($entry->getLastConfirmedAt()) {
                    continue;
                }

                // reminder already sent
                if ($entry->getLastReminderSent() && $entry->getLastReminderSent() > $oneDayAgo) {
                    continue;
                }

                if ($this->emailService->announceEra($entry)) {
                    $entry->setLastReminderSent();
                    DoctrineHelper::persistAndFlush($this->registry, $entry);
                } else {
                    $allSuccessful = false;
                }
            }

            if ($allSuccessful) {
                $era->setReminderSentAt();
                DoctrineHelper::persistAndFlush($this->registry, $era);
            }

            $message = 'Reminders sent for '.$era->getName().'. Successful: '.($allSuccessful ? 'yes' : 'no');
            $this->logger->info($message);
            $output->writeln($message);
        }

        return Command::SUCCESS;
    }
}
