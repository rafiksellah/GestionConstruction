<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Karser\Recaptcha3Bundle\Form\Recaptcha3Type;
use Karser\Recaptcha3Bundle\Validator\Constraints\Recaptcha3;
class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom',TextType::class,
            ['attr'=>
                ['class'=>'form-control',
                'placeholder'=>'Nom']
            ])
            ->add('prenom',TextType::class,
            ['attr'=>
                ['class'=>'form-control',
                'placeholder'=>'Prenom']
            ])
            ->add('phone',TextType::class,
            ['attr'=>
                ['class'=>'form-control',
                'placeholder'=>'Telephone']
            ])
            ->add('entreprise',TextType::class,
            ['attr'=>
                ['class'=>'form-control',
                'placeholder'=>'Entreprise']
            ])
            ->add('adresse',TextType::class,
            ['attr'=>
                ['class'=>'form-control',
                'placeholder'=>'Adresse']
            ])

            ->add('email',EmailType::class,
            ['attr'=>
                ['class'=>'form-control',
                'placeholder'=>'Email']
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'label'=>"J’accepte  la politique de confidentialité et je valide la création de mon compte",
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password',
                'class'=>'form-control',
                'placeholder'=>'Password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('captcha', Recaptcha3Type::class, [
                'constraints' => new Recaptcha3(),
                'action_name' => 'Inscription',
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
