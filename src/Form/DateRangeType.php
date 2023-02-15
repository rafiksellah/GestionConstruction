<?php
namespace App\Form;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DateRangeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('startDate', DateType::class, [
                'required' => true,
                'widget' => 'single_text',
                'input'  => 'datetime_immutable',
                'attr' => ['class' => 'form-control form-control-sm'],
            ])
            ->add('endDate', DateType::class, [
                'required' => true,
                'widget' => 'single_text',
                'input'  => 'datetime_immutable',
                'attr' => ['class' => 'form-control form-control-sm'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => DateRange::class,
        ]);
    }
}




