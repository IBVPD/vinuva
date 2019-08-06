<?php

namespace App\Form\Report\Hospital;

use App\Form\Filters\CountryFilterType;
use App\Form\Filters\YearMonthRangeFilterType;
use Lexik\Bundle\FormFilterBundle\Filter\Doctrine\ORMQuery;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class FilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('countries', CountryFilterType::class, [
                'apply_filter' => static function (ORMQuery $filterQuery, $field, $values) {
                    if (!empty($values['value'])) {
                        $qb = $filterQuery->getQueryBuilder();
                        $qb->andWhere('c.id = :filterCountry')->setParameter('filterCountry', $values['value']->getId());
                    }
                },
            ])
            ->add('date', YearMonthRangeFilterType::class, [
                'error_bubbling' => false,
            ]);
    }
}
