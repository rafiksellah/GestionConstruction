<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Wilaya;
use App\Entity\Commune;
use App\Entity\Profession;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;


class UsereditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('nom',TextType::class, [
            'label' => false,
                'attr' => ['class' => 'form-control'],
            ])
        ->add('prenom',TextType::class, [
            'label' => false,
                'attr' => ['class' => 'form-control'],
            ])
        ->add('phone',TextType::class, [
            'label' => false,
                'attr' => ['class' => 'form-control'],
            ])
        ->add('email', EmailType::class,[
            'constraints' => [
                new NotBlank([
                    'message' => 'Merci d\'entrer un e-mail',
                ]),
            ],
            'required' => true,
            'attr' => ['class' =>'form-control'],
        ])
            ->add('password', PasswordType::class, [
                'attr' => ['class' => 'form-control'],
                'required'=>false,
                // pour pouvoir envoyer un champ vide dans l'edit
                'empty_data' => '',
                'mapped'=>false,
                //-----------------
            ])
        ->add('entreprise',TextType::class, [
            'label' => false,
                'attr' => ['class' => 'form-control'],
            ])
        ->add('adresse',TextType::class, [
            'label' => false,
                'attr' => ['class' => 'form-control'],
            ])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}