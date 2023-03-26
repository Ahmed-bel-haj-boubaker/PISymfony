<?php

namespace App\Form;

use App\Entity\CategoryHebergement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
class CategoryHebergementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
          ->add('nomcategory', TextType::class, [
                'required' => true,
                'constraints' => [
                    new Length([
                        'min' => 3,
                        'max' => 50,
                        'minMessage' => 'Your name must be at least {{ limit }} characters long',
                        'maxMessage' => 'Your name cannot be longer than {{ limit }} characters'
                    ])
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CategoryHebergement::class,
        ]);
    }
}
