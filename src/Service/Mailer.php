<?php

namespace App\Service;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

class Mailer {
    
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendEmail($email, $token)
    {
        $email = (new TemplatedEmail())
            ->from('resgister@example.com')
            ->to(new Address($email))
            ->subject('Thanks for signing up!')

            // path of the Twig template to render
            ->htmlTemplate('/mailer/index.html.twig')

            // pass variables (name => value) to the template
            ->context([
                'token' => $token,
            ])
        ;

        $this->mailer->send($email);
    }

    

    
    public function sendPasswordResetEmail($email, $token,$firstName)
    {
        $email = (new TemplatedEmail())
            ->from('ResetPassword@example.com')
            ->to(new Address($email))
            ->subject('Reset-password')

            // path of the Twig template to render
          ->htmlTemplate('/mailer/resetPassEmail.html.twig')
           

            // pass variables (name => value) to the template
            ->context([
                'token' => $token,
                'firstName'=>$firstName
            
            ])
        ;

        $this->mailer->send($email);
    }
}