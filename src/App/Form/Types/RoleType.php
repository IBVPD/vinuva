<?php

namespace App\Form\Types;

use Paho\Vinuva\Models\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RoleType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'choices' => array_flip(User::$roles),
            'placeholder' => 'Select...'
        ]);
    }

    public function getParent(): string
    {
        return ChoiceType::class;
    }
}
