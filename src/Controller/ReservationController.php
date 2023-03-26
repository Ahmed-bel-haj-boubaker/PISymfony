<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\ReservationRepository;
use App\Repository\MatchFRepository;
use App\Repository\BilletRepository;
use App\Entity\Reservation;
use App\Entity\MatchF;
use App\Entity\Billet;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\ReservationType;
use App\Manager\ReservationManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\Bridge\Google\Transport\GmailSmtpTransport;
use Symfony\Component\Mailer\Mailer; 



class ReservationController extends AbstractController
{


    #[Route('/admin/readR', name: 'readReservation')]
    public function readR(ReservationRepository $repository): Response
    {
        $r =$repository->findAll();
        return $this->render('reservation/readR.html.twig', [
            'reservation' => $r,
        ]);
    }

    


    


/////////////// CREATE


    #[Route('/admin/createR', name: 'createReservation')]
    public function createR(ManagerRegistry $doctrine, ReservationRepository $repository, Request $request): Response
    {

        $reservation = new Reservation();
        
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);
       
        if ($form->isSubmitted() && $form->isValid())  {
            $numBillets = $form->get('nombreBillet')->getData();
            $em = $doctrine->getManager();
            $em->persist($reservation);
            for ($i = 0; $i < $numBillets; $i++) {
                $billet = new Billet();
                $billet->setReservation($reservation);
                $billet->setPrix($reservation->getMatchF()->getPrix());
                $reservation->addBillet($billet);
                $em->persist($billet);
            }
            $em->flush();
            return $this->redirectToRoute("readReservation", ['id' => $reservation->getId()]);
        }         
        return $this->renderForm('reservation/createR.html.twig', array('f' => $form));
        
    }

    #[Route('/createReservation/{match_id}', name: 'create_Reservation')]
    public function createReservation(ManagerRegistry $doctrine, ReservationRepository $repository, Request $request, $match_id, MatchFRepository $rep, MailerInterface $mailer): Response
    {
        $user = $this->getUser();
        $match =$rep->find($match_id);
        $reservation = new Reservation();
        $reservation->setDateResevation(new \DateTime());
        $reservation->setUser($user);
        $reservation->setMatchF($match);
        $form = $this->createForm(ReservationType::class, $reservation, [
            'user' => $user,
        ]);
        $form->handleRequest($request);
        $r =$repository->findAll();
        if ($form->isSubmitted() && $form->isValid())  {
            $reservation->setEtat('confirmée');
            $numBillets = $form->get('nombreBillet')->getData();
            $match->setNbBilletReserve($match->getNbBilletReserve
            () + $reservation->getNombreBillet());
            $em = $doctrine->getManager();
            $em->persist($reservation);
            for ($i = 0; $i < $numBillets; $i++) {
                $billet = new Billet();
                $billet->setReservation($reservation);
                $billet->setPrix($reservation->getMatchF()->getPrix());
                $reservation->addBillet($billet);
                $em->persist($billet);
            }
            $em->flush();

            $email = (new Email())
            ->from('hamdikhsib12@gmail.com')
            ->to($reservation->getUser()->getEmail())
            ->subject('Reservation créée')
            ->text("Votre reservation with l'ID {$reservation->getId()} a été crée.")
            ->html("<p>Succés</p>");
            
            $mailer->send($email);

        

           
            
            return $this->redirectToRoute("read_Billet", ['id' => $reservation->getId()]);
        }
               
        return $this->render('reservation/createReservation.html.twig', ['f' => $form->createView(),
            'reservation' => $r,
            
        ]);
    }


//////////////////////// UPDATE



    #[Route('/admin/updateR/{id}', name: 'updateReservation')]
    public function updateR(ManagerRegistry $doctrine, ReservationRepository $repository, Request $request, $id): Response
    {
        $reservation = $repository->find($id);
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);
        
        if ($form->isSubmitted())  {
            $em = $doctrine->getManager();
            $em->persist($reservation);
            $em->flush();
            return $this->redirectToRoute("readReservation");
        }         
        return $this->renderForm('reservation/updateR.html.twig', array('f' => $form));
        
    }


    #[Route('/updateReservation/{id}', name: 'update_Reservation')]
    public function updateReservation(ManagerRegistry $doctrine, ReservationRepository $repository, Request $request, $id): Response
    {
        $reservation = $repository->find($id);
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);
        
        if ($form->isSubmitted())  {
            $reservation->setEtat('modifiée');
            $em = $doctrine->getManager();
            $em->persist($reservation);
            $em->flush();
            return $this->redirectToRoute("read_Billet" ,['id' => $reservation->getId()]);
        }         
        return $this->renderForm('reservation/updateReservation.html.twig', array('f' => $form));
        
    }


/////////////////////// DELETE


    #[Route('/admin/deleteR/{id}', name: 'deleteReservation')]
    public function deleteR(ManagerRegistry $doctrine, EntityManagerInterface $entityManager, BilletRepository $rep, ReservationRepository $repository, $id, MailerInterface $mailer): Response
    {
        $reservation = $repository->find($id);
        $reservationIdToDelete = $reservation->getId();
        $billetToDelete = $rep->findBy([
            'reservation' => $reservationIdToDelete,
        ]);
        foreach ($billetToDelete as $billetToDelete) {
            $entityManager->remove($billetToDelete);
        }
        $em = $doctrine->getManager();
        $em->remove($reservation);
        $em->flush();

        $email = (new Email())
            ->from('hamdikhsib12@gmail.com')
            ->to($reservation->getUser()->getEmail())
            ->subject('Reservation annulée')
            ->text("Votre reservation est annulée.")
            ->html("<p>Succés</p>");
            
            $mailer->send($email);

        return $this->redirectToRoute("readReservation");
        
    }

    /**
     * @Route("/reservation/{id}/payment/show", name="payment", methods={"GET", "POST"})
     * @param Reservation $reservation
     * @return Response
     */
    
    public function payment(Reservation $reservation, ReservationManager $reservationManager): Response
    {
        
        return $this->render('reservation/paiementReservation.html.twig', [
            'user' => $this->getUser(),
            'intentSecret' => $reservationManager->intentSecret($reservation),
            'reservation' => $reservation
        ]);
    }

    /**
     * @Route("/reservation/subscription/{id}/paiement/load", name="subscription_paiement", methods={"GET", "POST"})
     * @param Reservation $reservation
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @throws \Exception
     
     */
    public function subscription(Reservation $reservation, Request $request, ReservationManager $reservationManager){
        

        if($request->getMethod() === "POST") {
            $resource = $reservationManager->stripe($_POST, $reservation);

            if(null !== $resource) {
                $reservationManager->create_subscription( $reservation);

                return $this->render('reservation/reponsePaiement.html.twig', [
                    'reservation' => $reservation
                ]);
            }
        }

        return $this->redirectToRoute('payment', ['id' => $reservation->getId()]);
    }

    

}

 
