<?php
declare(strict_types=1);

namespace App\Form\Admin\Country;

use Doctrine\ORM\EntityRepository;
use Lexik\Bundle\FormFilterBundle\Filter\FilterOperands;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type\ChoiceFilterType;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type\EntityFilterType;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type\TextFilterType;
use Paho\Vinuva\Models\Country;
use Paho\Vinuva\Models\Hospital;
use Paho\Vinuva\Models\Region;
use Paho\Vinuva\Models\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class FilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextFilterType::class, ['condition_pattern' => FilterOperands::STRING_CONTAINS])
            ->add('iso2', TextFilterType::class, ['condition_pattern' => FilterOperands::STRING_CONTAINS])
            ->add('fips', TextFilterType::class, ['condition_pattern' => FilterOperands::STRING_CONTAINS])
            ->add('region', EntityFilterType::class, [
                'placeholder' => 'Please Select...',
                'class' => Region::class,
                'query_builder' => static function(EntityRepository $repository) {
                    return $repository->createQueryBuilder('r')->orderBy('r.name');
                }
            ]);
    }
}
