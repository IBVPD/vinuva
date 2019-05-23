<?php

namespace App\Form\Filters;

use Doctrine\ORM\EntityRepository;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type\DateRangeFilterType;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type\EntityFilterType;
use Paho\Vinuva\Models\Country;
use Paho\Vinuva\Models\Hospital;
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
