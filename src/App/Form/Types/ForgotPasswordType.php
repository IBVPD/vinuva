<?php

namespace App\Form\Types;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class ForgotPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('_username', TextType::class, [
            'label' => false,
            'attr' => ['placeholder' => 'Username', 'autocomplete' => 'on username'],
            'wrapper_class' => 'col-sm-9 col-sm-offset-1']);
    }

    public function getBlockPrefix(): ?string
    {
        return null;
    }
}
