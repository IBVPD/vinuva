<?php
declare(strict_types=1);

namespace App\Form\Admin\Hospital;

use Doctrine\ORM\EntityRepository;
use Lexik\Bundle\FormFilterBundle\Filter\FilterOperands;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type\BooleanFilterType;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type\EntityFilterType;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type\TextFilterType;
use Paho\Vinuva\Models\Country;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class FilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextFilterType::class, ['condition_pattern' => FilterOperands::STRING_CONTAINS])
            ->add('short', TextFilterType::class, ['condition_pattern' => FilterOperands::STRING_CONTAINS])
            ->add('local', TextFilterType::class, ['condition_pattern' => FilterOperands::STRING_CONTAINS])
            ->add('active', BooleanFilterType::class)
            ->add('country', EntityFilterType::class, [
                'placeholder' => 'Select...',
                'class' => Country::class,
                'query_builder' => static function(EntityRepository $repository) {
                    return $repository->createQueryBuilder('c')->orderBy('c.name');
                }
            ]);
    }
}
