<?php
declare(strict_types=1);

namespace App\Form\Admin\Country;

use App\Form\Types\RegionType;
use Paho\Vinuva\Models\Country;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('code', TextType::class, [
                'required' => true,
                'label' => 'Code'
            ])
            ->add('name', TextType::class, [
                'required' => true,
                'label' => 'Name'
            ])
            ->add('iso2', TextType::class, [
                'required' => false,
                'label' => 'ISO'
            ])
            ->add('fips', TextType::class, [
                'required' => false,
                'label' => 'FIPS',
            ])
            ->add('region', RegionType::class, [
                'required' => true,
                'label' => 'Region',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Country::class,
            'empty_data' => false,
        ]);
    }
}
