<?php

namespace App\Form;

use App\Entity\Plan;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class PlanDecoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('noteDecortiqueur', TextareaType::class, [
            'label' => false,
                'attr' => ['class' => 'form-control'],
            ])  
            ->add('tonnageTS', TextType::class, [
            'label' => false,
                'attr' => ['class' => 'form-control'],
            ])  
            ->add('tonnageCF', TextType::class, [
            'label' => false,
                'attr' => ['class' => 'form-control'],
            ])  
            ->add('tonnageCA', TextType::class, [
            'label' => false,
                'attr' => ['class' => 'form-control'],
            ])  
            ->add('fichierDecor',FileType::class,[
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
