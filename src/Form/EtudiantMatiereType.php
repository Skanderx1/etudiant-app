<?php

namespace App\Form;

use App\Entity\EtudiantMatiere;
use App\Entity\Matiere;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EtudiantMatiereType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('matiere', EntityType::class, [
                'class' => Matiere::class,
                'choice_label' => 'name',
                'placeholder' => 'Choisir une matière',
            ])
            ->add('absences', null, [
                'label' => 'Absences initiales',
                'required' => false,
                'attr' => ['min' => 0],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => EtudiantMatiere::class,
        ]);
    }
}
