<?php

namespace App\Form;

use App\Form\Types\CountryType;
use App\Form\Types\HospitalType;
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
            ->add('country', CountryType::class, ['required' => true] )
            ->add('hospital', HospitalType::class, ['required' => true])
            ->add('year', IntegerType::class, ['constraints' => [new GreaterThanOrEqual(2000)]])
            ->add('month', IntegerType::class, ['constraints' => [new Range(['min' => 1, 'max' => 12])]]);
    }
}
