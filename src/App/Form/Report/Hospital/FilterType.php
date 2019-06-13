<?php

namespace App\Form\Report\Hospital;

use App\Form\Filters\CountryFilterType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class FilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('countries', CountryFilterType::class);
    }
}
