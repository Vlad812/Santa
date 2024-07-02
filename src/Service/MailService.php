<?php

namespace App\Service;

use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class MailService implements MailServiceInterface
{
    /**
     * @param MailerInterface $mailer
     */
    public function __construct(protected MailerInterface $mailer)
    {
    }

    /**
     * @param string $email
     * @param string $subject
     * @param string $msg
     * @return void
     * @throws TransportExceptionInterface
     */
    public function sendEmail(string $email, string $subject, string $msg): void
    {
        $email = (new Email())
            ->from('santa@example.com')
            ->to($email)
            ->subject($subject)
            ->text($msg);

        $this->mailer->send($email);
    }
}