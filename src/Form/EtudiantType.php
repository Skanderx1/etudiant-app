<?php

namespace App\Form;

use App\Entity\Etudiant;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
class EtudiantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
    ->add('nom')
    ->add('prenom')
    ->add('email')
    ->add('nbreAbsence')
    // ...
    ->add('photoFile', FileType::class, [
        'label' => 'Photo (JPEG/PNG, max 2MB)',
        'mapped' => false,
        'required' => false,
        'constraints' => [
            new File([
                'maxSize' => '2048k',
                'mimeTypes' => [
                    'image/jpeg',
                    'image/png',
                    'image/webp'
                ],
                'mimeTypesMessage' => 'Please upload a valid image (JPEG, PNG, or WEBP)',
            ])
        ],
    ])
;
        
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Etudiant::class,
        ]);
    }
}
