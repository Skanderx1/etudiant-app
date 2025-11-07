<?php

namespace App\Form;

use App\Entity\Absence;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AbsenceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('etudiant', EntityType::class, [
                'class' => 'App\Entity\Etudiant',
                'choice_label' => fn($e) => $e->getNom() . ' ' . $e->getPrenom(),
            ])
            ->add('matiere', EntityType::class, [
                'class' => 'App\Entity\Matiere',
                'choice_label' => 'name',
            ])
            ->add('nbreAbsences', IntegerType::class)
            ->add('save', SubmitType::class, [
                'label' => 'Enregistrer',
                'attr' => ['class' => 'btn btn-primary']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Absence::class,
        ]);
    }
}
