<?php

namespace App\Form;

use App\Entity\MatchF;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;


class MatchFType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('heureDebM')
            ->add('heurefinM')
            ->add('dateMatch')
            ->add('resultatA')
            ->add('resultatB')
            ->add('prix')
            ->add('tournoi')
            ->add('typeMatch')
            ->add('stade')
            ->add('equipeA')
            ->add('equipeB')
            ->add('nbBilletTotal')
            ->add('nbBilletReserve')
            ->add('image', FileType::class, [
                'label' => 'image 1',

                
                'mapped' => false,

                
                'required' => false,

                
                'constraints' => [
                    new File([
                        'maxSize' => '2000k',
                        
                        'mimeTypesMessage' => 'Please upload a valid image',
                    ]),
                    
                    
                ],
            ])
            ->add('image2', FileType::class, [
                'label' => 'image2',

                
                'mapped' => false,

                
                'required' => false,

                
                'constraints' => [
                    new File([
                        'maxSize' => '2000k',
                        
                        'mimeTypesMessage' => 'Please upload a valid image',
                    ]),
                    
                    
                ],
            ])
            
        ;

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => MatchF::class,
            'constraints' => [
                new Callback([$this, 'validateEquipes']),
                new Callback([$this, 'validateHeureDebM']),
            ],
        ]);
    }

    public function validateEquipes($data, ExecutionContextInterface $context): void
    {
        if ($data->getEquipeA() === $data->getEquipeB()) {
            $context->buildViolation('Les équipes ne peuvent pas être identiques')
                ->atPath('equipeA')
                ->addViolation();
        }
    }

    public function validateHeureDebM($data, ExecutionContextInterface $context): void
    {
        if ($data->getHeureDebM() >= $data->getHeurefinM()) {
            $context->buildViolation('Heure de début doit être avant l heure de fin')
                ->atPath('heureDebM')
                ->addViolation();
        }
    }
}
