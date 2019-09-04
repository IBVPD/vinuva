<?php

namespace App\Form\Types;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;

class ForgotPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('_username', EmailType::class, [
            'label' => false,
            'attr' => ['placeholder' => 'Email', 'autocomplete' => 'on email'],
            'wrapper_class' => 'col-sm-9 col-sm-offset-1']);
    }

    public function getBlockPrefix(): ?string
    {
        return null;
    }
}
