<?php

namespace App\Form;

use App\Entity\Controle;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ControleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('HoraireTTDebut')
            ->add('HoraireTTFin')
            ->add('HoraireNonTTDebut')
            ->add('HoraireNonTTFin')
            ->add('Date')
            ->add('Placement')
            ->add('Surveillant')
            ->add('Referent')
            ->add('Module')
            ->add('Promotion')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Controle::class,
        ]);
    }
}
