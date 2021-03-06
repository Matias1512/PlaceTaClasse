<?php

namespace App\Form;

use App\Entity\Etudiant;
use App\Entity\Promotion;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EtudiantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Nom')
            ->add('Prenom')
            ->add('Promotion', EntityType::class, [
                // looks for choices from this entity
                'class' => Promotion::class,
            
                // uses the User.username property as the visible option string
                'choice_label' => 'nomLong',
            
                // used to render a select box, check boxes or radios
                 //'multiple' => true,
                 //'expanded' => true,
            ])
            ->add('Tp')
            ->add('TierTemps')
            ->add('Ordinateur')
            ->add('Mail')
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Etudiant::class,
        ]);
    }
}
