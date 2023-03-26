<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ChatController extends AbstractController
{
    #[Route('/', name: 'tunisport',methods: ['GET', 'POST'])]
    public function index(): Response
    {
        return $this->render('front.html.twig');
    }
}
