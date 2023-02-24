<?php

namespace App\Form;

use App\Entity\Plan;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class Plan1Type extends AbstractType
{
    private $userRepository;
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('name', TextType::class, [
            'label' => false,
                'attr' => ['class' => 'form-control'],
            ])  
            ->add('user', EntityType::class, [
                'class' => User::class,
                // 'choices' => $userChoices,
                'label' => 'Sélectionnez un client',
                'required' => true,
                'attr' => ['class' => 'form-control'],
                'query_builder' => function (UserRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->where('u.isClient = 1');
                }
            ]) 
            ->add('chantier',TextType::class, [
                'label' => false,
                    'attr' => ['class' => 'form-control'],
                ])
            ->add('priorite', ChoiceType::class, [
                'choices' => [
                    'Normal' => 'Normal',
                    'Urgent' => 'Urgent',
                ],
                'attr' => ['class' => 'form-control'],
                'expanded' => false,
                'multiple' => false,
                'label' => 'Rôles' 
            ])
            ->add('notes',TextareaType::class,
            [
                'label' => false,
                'attr' => ['class' => 'form-control'],
                ])
            ->add('date',DateTimeType::class, [
                'widget' => 'single_text',
                'attr' => ['class' => 'input-group date'],
                'label' => false,
            ])
            ->add('fichiers',FileType::class,[
                'label' => 'Fichier pdf',
                'multiple' => true,
                'mapped' => false,
                'required' => false]
                )
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Plan::class,
        ]);
    }
}
