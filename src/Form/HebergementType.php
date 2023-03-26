<?php

namespace App\Form;
use App\Entity\CategoryHebergement;
use Symfony\Component\validator\Constraints as Assert;
use App\Entity\Hebergement;
use App\Entity\Localisation;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
class HebergementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('image', FileType::class, [
            'label' => 'image hebergement',

            // unmapped means that this field is not associated to any entity property
            'mapped' => false,

            // make it optional so you don't have to re-upload the PDF file
            // every time you edit the Product details
            'required' => false,

            // unmapped fields can't define their validation using annotations
            // in the associated entity, so you can use the PHP constraint classes
          //'constraints' => [
        //          new File([
        //              'maxSize' => '1024k',
        //              'mimeTypes' => [
        //                  'image/jpg',
        //                  'image/png',
        //              ],
        //              'mimeTypesMessage' => 'Please upload a valid PDF document',
        //         ])
        //    ],
        ])
            ->add('nomHeberg')
            ->add('deschebergement', TextareaType::class, [
                'label' => 'description',
                'attr' => [
                    'class' => 'deschebergement'::class,
                ]
            ])
            ->add('localisation',Entitytype::class,[
                'class'=>Localisation::class,
                'choice_label'=>'lieux',

            ])
            ->add('categoryHebergement',Entitytype::class,[
                'class'=>CategoryHebergement::class,
                'choice_label'=>'nomcategory',

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Hebergement::class,
        ]);
    }
}
