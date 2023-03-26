<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ReservationRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\MatchFRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Equipe;
use App\Repository\EquipeRepository;
class FrontController extends AbstractController
{
    #[Route('/frontMatch', name: 'frontMatch')]
    public function front(MatchFRepository $repository, Request $request, ManagerRegistry $doctrine,EquipeRepository $rep): Response
    {
        $m = $repository->findBy([], ['dateMatch' => 'DESC']);
        $finishedMatches = $repository->findFinishedMatches();
        $upcomingMatches = $repository->findUpcomingMatches();

        $entityManager = $doctrine->getManager();
        $equipe1 = $doctrine->getRepository(Equipe::class)->findOneBy(['slug' => 'EST']);
        $equipe1->setClassement(1);
        $equipe1->setPoints(7);
        $entityManager->persist($equipe1);

        $equipe2 = $doctrine->getRepository(Equipe::class)->findOneBy(['slug' => 'CA']);
        $equipe2->setClassement(4);
        $equipe2->setPoints(4);
        
        $entityManager->persist($equipe2);

        $equipe3 = $doctrine->getRepository(Equipe::class)->findOneBy(['slug' => 'ESS']);
        $equipe3->setClassement(3);
        $equipe3->setPoints(6);
        $entityManager->persist($equipe3);

        $equipe4 = $doctrine->getRepository(Equipe::class)->findOneBy(['slug' => 'CSS']);
        $equipe4->setClassement(8);
        $equipe4->setPoints(1);
        $entityManager->persist($equipe4);

        $equipe5 = $doctrine->getRepository(Equipe::class)->findOneBy(['slug' => 'USM']);
        $equipe5->setClassement(2);
        $equipe5->setPoints(7);
        $entityManager->persist($equipe5);

        $equipe6 = $doctrine->getRepository(Equipe::class)->findOneBy(['slug' => 'OB']);
        $equipe6->setClassement(6);
        $equipe6->setPoints(2);
        $entityManager->persist($equipe6);

        $equipe7 = $doctrine->getRepository(Equipe::class)->findOneBy(['slug' => 'USBG']);
        $equipe7->setClassement(5);
        $equipe7->setPoints(3);
        $entityManager->persist($equipe7);

        $equipe8 = $doctrine->getRepository(Equipe::class)->findOneBy(['slug' => 'UST']);
        $equipe8->setClassement(7);
        $equipe8->setPoints(2);
        $entityManager->persist($equipe8);

        $entityManager->flush();

        $teams = $rep->getClassementEquipe();

        return $this->render('frontMatch.html.twig', [
            'finishedMatches' => $finishedMatches,
            'upcomingMatches' => $upcomingMatches,
            'teams' => $teams,
        ]);
    }


    
}
