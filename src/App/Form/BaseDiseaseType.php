<?php

namespace App\Form;

use Doctrine\ORM\EntityRepository;
use Paho\Vinuva\Models\Country;
use Paho\Vinuva\Models\Hospital;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\Range;

class BaseDiseaseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('country', EntityType::class, [
                'class' => Country::class,
                'query_builder' => static function (EntityRepository $repository) {
                    return $repository->createQueryBuilder('c')->orderBy('c.name');
                },
                'placeholder' => '',
            ])
            ->add('hospital', EntityType::class, [
                'class' => Hospital::class,
                'query_builder' => static function (EntityRepository $repository) {
                    return $repository->createQueryBuilder('h')->orderBy('h.name');
                },
                'choice_attr' => static function ($choiceValue) {
                    return ['data-country' => $choiceValue->getCountry()->getId()];
                },
                'placeholder' => '',
            ])
            ->add('year', IntegerType::class, ['constraints' => [new GreaterThanOrEqual(2000)]])
            ->add('month', IntegerType::class, ['constraints' => [new Range(['min' => 1, 'max' => 12])]]);
    }
}
