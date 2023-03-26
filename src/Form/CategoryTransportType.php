<?php

namespace App\Form;
use App\Entity\Transport;
use App\Form\Entitytype;
use App\Entity\CategoryTransport;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints as Assert;

class CategoryTransportType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('typetransport', TextType::class, [
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Please enter a value for the transport type.'
                    ])
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CategoryTransport::class,
        ]);
    }
}
