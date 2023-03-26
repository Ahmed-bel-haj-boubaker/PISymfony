<?php

namespace App\Service;

use App\Entity\Billet;
use App\Entity\Reservation;
use Stripe\Stripe;

class StripeService
{
    private $privateKey;

    public function __construct()
    {
        if($_ENV['APP_ENV']  === 'dev') {
            $this->privateKey = $_ENV['STRIPE_SECRET_KEY_TEST'];
        } else {
            $this->privateKey = $_ENV['STRIPE_SECRET_KEY_LIVE'];
        }
    }

    /**
     * @param Reservation $reservation
     * @return \Stripe\PaymentIntent
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function paymentIntent(Reservation $reservation)
    {
        \Stripe\Stripe::setApiKey($this->privateKey);

        return \Stripe\PaymentIntent::create([
            'amount' => $reservation->getMatchF()->getPrix() * 100,
            'currency' => 'eur',
            'payment_method_types' => ['card']
        ]);
    }

    public function paiement(
        $amount,
        $currency,
        $description,
        array $stripeParameter
    )
    {
        \Stripe\Stripe::setApiKey($this->privateKey);
        $payment_intent = null;

        if(isset($stripeParameter['stripeIntentId'])) {
            $payment_intent = \Stripe\PaymentIntent::retrieve($stripeParameter['stripeIntentId']);
        }

        if($stripeParameter['stripeIntentStatus'] === 'succeeded') {
            //TODO
        } else {
            $payment_intent->cancel();
        }

        return $payment_intent;
    }

    /**
     * @param array $stripeParameter
     * @param Reservation $reservation
     * @return \Stripe\PaymentIntent|null
     */
    public function stripe(array $stripeParameter, Reservation $reservation)
    {
        return $this->paiement(
            $reservation->getMatchF()->getPrix() * 100,
            'eur',
            'reservation',
            $stripeParameter
        );
    }
}





