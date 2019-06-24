<?php

namespace App\Form\Filters;

use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\QueryBuilder;
use Lexik\Bundle\FormFilterBundle\Filter\Doctrine\ORMQuery;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type\BooleanFilterType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class BaseDiseaseFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('country', CountryFilterType::class)
            ->add('hospital', HospitalFilterType::class)
            ->add('date', YearMonthRangeFilterType::class)
            ->add('verified', BooleanFilterType::class, [
                'apply_filter' => static function (ORMQuery $filterQuery, string $field, array $values) {
                    if (!empty($values['value'])) {
                        /** @var Expr $expresion */
                        $expresion = $filterQuery->getExpr();
                        $expresion->isNull($field);

                        /** @var QueryBuilder $qb */
                        $qb = $filterQuery->getQueryBuilder();
                        if ($values['value'] === BooleanFilterType::VALUE_YES) {
                            $qb->andWhere("$field = 1");
                            return;
                        }

                        $qb->andWhere("$field = false OR $field IS NULL");
                    }
                },
            ]);
    }

}
