<?php

namespace App\Manager;

use App\Entity\Billet;
use App\Entity\Reservation;
use App\Entity\User;
use App\Service\StripeService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ReservationManager
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @var StripeService
     */
    protected $stripeService;

    /**
     * @param EntityManagerInterface $entityManager
     * @param StripeService $stripeService
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        StripeService $stripeService
    ) {
        $this->em = $entityManager;
        $this->stripeService = $stripeService;
    }

    public function getReservations()
    {
        return $this->em->getRepository(Reservation::class)
            ->findAll();
    }

    /**
     * @param User $user
     * @return mixed
     */
    public function countSoldeBillet(User $user)
    {
        return $this->em->getRepository(Billet::class)
            ->countSoldBillet($user);
    }

    public function getBillets(User $user)
    {
        return $this->em->getRepository(Billet::class)
            ->findByUser($user);
    }

    public function intentSecret(Reservation $reservation)
    {
        $intent = $this->stripeService->paymentIntent($reservation);

        return $intent['client_secret'] ?? null;
    }

    /**
     * @param array $stripeParameter
     * @param Reservation $reservation
     * @return array|null
     */
    public function stripe(array $stripeParameter, Reservation $reservation)
    {
        
        $data = $this->stripeService->stripe($stripeParameter, $reservation);

        
        return $data;
    }

    /**
     * @param array $resource
     * @param Reservation $reservation
     * @param User $user
     */
    public function create_subscription( Reservation $reservation)
    {
        $billet = new Billet();
        $billet->setReservation($reservation);
        $billet->setPrix($reservation->getMatchF()->getPrix());
        
        
        $this->em->persist($billet);
        $this->em->flush();
    }
}
