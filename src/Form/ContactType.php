<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('prenom')
            ->add('nom')
            ->add('email')
            ->add('sujet')
            ->add('category', ChoiceType::class, [
                'choices' => [
                    'Hôtel' => 'Hôtel',
                    'Chambre' => 'Chambre',
                    "Restaurant" => "Restaurant",
                    'Spa' => 'Spa',
                ]
            ])
            ->add('civilite', ChoiceType::class, [
                'choices' => [
                    'M' => 'Homme',
                    'Mme' => 'Femme',
                    "Autre" => "Autre"
                ]
            ])
            ->add('content', TextareaType::class)

            

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
