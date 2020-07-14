<?php
declare(strict_types=1);

namespace App\Form\Admin\User;

use App\Form\Filters\CountryFilterType;
use App\Form\Filters\HospitalFilterType;
use Lexik\Bundle\FormFilterBundle\Filter\Doctrine\ORMQuery;
use Lexik\Bundle\FormFilterBundle\Filter\FilterOperands;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type\BooleanFilterType;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type\ChoiceFilterType;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type\TextFilterType;
use Paho\Vinuva\Models\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class FilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextFilterType::class, ['label' => 'Name', 'condition_pattern' => FilterOperands::STRING_CONTAINS])
            ->add('login', TextFilterType::class, ['label' => 'Login', 'condition_pattern' => FilterOperands::STRING_CONTAINS])
            ->add('email', TextFilterType::class, ['label' => 'Email', 'condition_pattern' => FilterOperands::STRING_CONTAINS])
            ->add('active', BooleanFilterType::class, ['label' => 'Active Account?',])
            ->add('role', ChoiceFilterType::class, [
                'label' => 'Role',
                'placeholder' => 'Select...',
                'choices' => ['Admin' => User::ROLE_ADMIN, 'Verifier' => User::ROLE_VERIFIER, 'Collector' => User::ROLE_COLLECTOR, 'Reader' => User::ROLE_READER],
            ])
            ->add('country', CountryFilterType::class, ['label' => 'Country'])
            ->add('hospital', HospitalFilterType::class, [
                'label' => 'Hospital',
                'apply_filter' => static function (ORMQuery $filterQuery, $field, $values) {
                    if (!empty($values['value'])) {
                        $qb = $filterQuery->getQueryBuilder();
                        $qb
                            ->innerJoin($values['alias'].'.hospitals','hsp')
                            ->andWhere('hsp.id = :filterHospitalId')->setParameter('filterHospitalId', $values['value']->getId());
                    }
                },
            ]);
    }
}
