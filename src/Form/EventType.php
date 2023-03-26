<?php

namespace App\Form;

use App\Entity\Event;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Image;


class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomEvent')
            ->add('dateEvent')
            ->add('heureDeb')
            ->add('heureFin')
            ->add('localisation')
            ->add('typeEvent')
            ->add('image', FileType::class, [
                'label' => 'image du produit',
    
                // unmapped means that this field is not associated to any entity property
                'mapped' => false,
    
                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,
    
                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new File([
                        'maxSize' => '10244k',
                        'mimeTypes' => [
                            'image/jpg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid PDF document',
                    ])
                ],
            ]) 
            ->add('videourl')
            ->add('save', SubmitType::Class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
