<?php

namespace App\Service;

interface MailServiceInterface
{
    /**
     * @param string $email
     * @param string $subject
     * @param string $msg
     * @return void
     */
    public function sendEmail(string $email, string $subject, string $msg): void;
}