<?php

namespace App\Controller;
use App\Entity\User;
use App\Repository\UserRepository;
use SebastianBergmann\Template\Template;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;
class MailerController extends AbstractController
{  
    #[Route('/email')]
    public function sendEmail(MailerInterface $mailer,UserRepository $repo): Response
    {   $user = $repo->findAll();
        $email = (new TemplatedEmail())
            ->from('ahmed.belhajboubaker@esprit.tn')
            ->to('ahmed.belhajboubaker@esprit.tnefsf')
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('Time for Symfony Mailer!')
            ->text('Sending emails is fun again!')
            ->htmlTemplate('/mailer/index.html.twig');
            
        $mailer->send($email);


    
        return $this->render('mailer/index.html.twig');
    }
}
