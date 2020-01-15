<?php

namespace App\Form;

use Paho\Vinuva\Models\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('login')
            ->add('email')
            ->add('phone', null, ['required' => false])
            ->add('address', null, ['required' => false])
            ->add('locale', ChoiceType::class, [
                'choices' => [
                    'English' => 'en',
                    'Spanish' => 'es',
                    'Portuguese' => 'pt',
                    'French' => 'fr',
                ],
                'required' => false,
                'placeholder' => 'Select...',
            ])
            ->add('plainPassword',RepeatedType::class, [
                'required' => false,
                'type' => PasswordType::class,
                'first_name' => 'Password',
                'second_name' => 'Re-Enter'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
