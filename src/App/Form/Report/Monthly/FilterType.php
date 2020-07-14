<?php

namespace App\Form\Report\Monthly;

use App\Form\Filters\CountryFilterType;
use App\Form\Filters\HospitalFilterType;
use App\Form\Filters\YearMonthRangeFilterType;
use Lexik\Bundle\FormFilterBundle\Filter\Doctrine\ORMQuery;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('disease', ChoiceType::class, [
                'required' => false,
                'placeholder' => 'All',
                'choices' => ['Meningitis' => 'meningitis', 'Pneumonia' => 'pneumonia', 'Rotavirus' => 'rotavirus'],
                'apply_filter' => static function(ORMQuery $filterQuery, string $field, array $values) {
                },
                'label' => 'Disease'
            ])
            ->add('country', CountryFilterType::class)
            ->add('hospital', HospitalFilterType::class)
            ->add('date', YearMonthRangeFilterType::class, [
                'error_bubbling' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'allow_extra_fields' => true,
            'error_bubbling' => false,
        ]);
    }
}
