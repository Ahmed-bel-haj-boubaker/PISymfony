<?php

namespace App\Form;

use App\Entity\Commentaire;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Length;

class CommentaireFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('contenuCom', TextType::class,[
                'constraints' => [
                    new Length(['min' => 6])]
                ])
            // ->add('dateC')
            // ->add('blog')
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Commentaire::class,
        ]);
    }
}
