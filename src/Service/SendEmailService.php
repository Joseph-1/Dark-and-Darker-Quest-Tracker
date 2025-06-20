<?php

namespace App\Service;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class SendEmailService
{
    public function __construct(private MailerInterface $mailer)
    {

    }
        public function send(
            string $from,
            string $to,
            string $subject,
            string $template,
            array $context,
        ): void
        {
            // Create email
            $email = (new TemplatedEmail()) // Allow to  add sender, recipient etc...
                ->from($from)
                ->to($to)
                ->subject($subject)
                ->htmlTemplate("emails/$template.html.twig")
                ->context($context); // for sending variable, token here

            // Send email
            $this->mailer->send($email);
        }
}
