<?php

namespace App\Form\Filters;

use Lexik\Bundle\FormFilterBundle\Filter\Doctrine\ORMQuery;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContext;

class YearMonthRangeFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('from', YearMonthFilterType::class, ['required' => false])
            ->add('to', YearMonthFilterType::class, ['required' => false]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'required' => false,
            'constraints' => [new Callback(['callback' => [$this,'validate']])],
            'apply_filter' => static function (ORMQuery $filterQuery, $field, array $values) {
                $from = $values['value']['from'];
                $to   = $values['value']['to'];
                if (!empty($from['month'])) {
                    $filterQuery->getQueryBuilder()->andWhere($values['alias'] . '.month >= :filterFromMonth')->setParameter('filterFromMonth', $from['month']);
                }

                if (!empty($from['year'])) {
                    $filterQuery->getQueryBuilder()->andWhere($values['alias'] . '.year >= :filterFromYear')->setParameter('filterFromYear', $from['year']);
                }

                if (!empty($to['month'])) {
                    $filterQuery->getQueryBuilder()->andWhere($values['alias'] . '.month <= :filterToMonth')->setParameter('filterToMonth', $to['month']);
                }

                if (!empty($to['year'])) {
                    $filterQuery->getQueryBuilder()->andWhere($values['alias'] . '.year <= :filterToYear')->setParameter('filterToYear', $to['year']);
                }
            },
        ]);
    }

    public function validate($value, ExecutionContext $context): void
    {
        if (isset($value['from']['year'], $value['to']['year']) && $value['from']['year'] > $value['to']['year']) {
            $context->buildViolation('From must be less than to')->addViolation();
        }

        if (isset($value['from']['month'], $value['to']['month']) && $value['from']['month'] > $value['to']['month']) {
            $context->buildViolation('From must be less than to')->addViolation();
        }
    }
}
