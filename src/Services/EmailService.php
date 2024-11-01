<?php

namespace App\Services;

use App\Entity\EraEntry;
use App\Services\Interfaces\EmailServiceInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Contracts\Translation\TranslatorInterface;

readonly class EmailService implements EmailServiceInterface
{
    public function __construct(private MailerInterface $mailer, private TranslatorInterface $translator, private LoggerInterface $logger)
    {
    }

    public function announceEra(EraEntry $entry): bool
    {
        $subject = $this->translator->trans('announce_era.subject', [], 'emails');

        if ($entry->getLastReminderSent()) {
            $reminder = $this->translator->trans('reminder.subject', [], 'emails');
            $subject = $reminder.' '.$subject;
        }

        $email = (new TemplatedEmail())
            ->to($entry->getEmail())
            ->subject($subject)
            ->textTemplate('emails/announce_era.txt.twig')
            ->context(['entry' => $entry]);

        return $this->sendMail($email);
    }

    public function sendMail(Email $email): bool
    {
        try {
            $this->mailer->send($email);

            return true;
        } catch (TransportExceptionInterface $exception) {
            $this->logger->error('email send failed', ['exception' => $exception]);

            return false;
        }
    }
}
