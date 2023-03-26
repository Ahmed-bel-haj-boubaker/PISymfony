<?php

namespace App\Form;

use App\Entity\Reclamation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Gregwar\CaptchaBundle\Type\CaptchaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
class ReclamationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            
            ->add('sujet', TextareaType::class, [
                'label' => 'sujet',
                'attr' => [
                    'class' => 'sujet'::class,
                ]
            ])
            
            ->add('descreption', TextareaType::class, [
                'label' => 'descreption',
                'attr' => [
                    'class' => 'descreption'::class,
                ]
            ])
            
           
            ->add('email', TextareaType::class, [
                'label' => 'email',
                'attr' => [
                    'class' => 'email'::class,
                ]
            ])
           
            
            ;
            
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reclamation::class,
        ]);
    }

}
