<?php

namespace App\Form\Filters;

use Lexik\Bundle\FormFilterBundle\Filter\Form\Type\DateRangeFilterType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class BaseDiseaseFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('country', CountryFilterType::class)
            ->add('hospital', HospitalFilterType::class)
            ->add('date', DateRangeFilterType::class, [
                'left_date_options' => ['label' => 'From'],
                'right_date_options' => ['label' => 'To'],
            ]);
    }
}
