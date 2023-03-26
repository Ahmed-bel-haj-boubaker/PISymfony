<?php

namespace App\Form;

use App\Entity\Reservation;
use App\Entity\User;
use App\Entity\MatchF;
use Symfony\Component\Form\AbstractType;
use App\Form\UserType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


class ReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $options['user'];
        $match = $options['matchF'];

        $builder
            ->add('nombreBillet')
            ->add('user', EntityType::class, [
                'class' => User::class,
                'data' => $user,
                'disabled' => true,
                'choice_label' => function ($user) {
                    return sprintf('%s, %s, %s', $user->getUserName(), $user->getEmail(), $user->getPhone());
                }
            ])
            ->add('Etat', HiddenType::class, [
                'data' => 'en attente', 
            ])
            ->add('save', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
            'user' => null,
            'matchF' => null,
        ]);
    }
}
