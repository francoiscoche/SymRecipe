<?php

namespace App\Service;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class MailService {

private MailerInterface $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendMail(string $from, string $subject, string $htmlTemplate, array $context, string $to = "admin@admin.com"):void
    {

        $email = (new TemplatedEmail())
        ->from($from)
        ->to($to)
        ->subject($subject)
        ->htmlTemplate($htmlTemplate)
        ->context([
            'contact' => $context
        ]);
        // ->text($contact->getMessage())

        $this->mailer->send($email);
    }
}