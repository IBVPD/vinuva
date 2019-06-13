<?php

namespace App\Form\Report\User;

use App\Form\Filters\CountryFilterType;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type\BooleanFilterType;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type\ChoiceFilterType;
use Paho\Vinuva\Models\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class FilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('role', ChoiceFilterType::class, [
                'choices' => array_flip(User::$roleLabels),
                'placeholder' => 'Select...'])
            ->add('country', CountryFilterType::class)
            ->add('active', BooleanFilterType::class);
    }
}
