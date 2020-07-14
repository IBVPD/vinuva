<?php
declare(strict_types=1);

namespace App\Form\Admin\Country;

use App\Form\Types\RegionType;
use Paho\Vinuva\Models\Country;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class CreateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('code', TextType::class, [
                'required' => true,
                'label' => 'Code',
                'constraints' => [new Regex('/^[a-z0-9]+$/i')]
            ])
            ->add('name', TextType::class, [
                'required' => true,
                'label' => 'Name',
                'constraints' => [new NotBlank()]
            ])
            ->add('region', RegionType::class, [
                'required' => true,
                'label' => 'Region'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Country::class,
            'invalid_message' => 'Missing required name field',
            'empty_data' => static function (FormInterface $form) {
                $code = $form['code']->getData();
                $name   = $form['name']->getData();
                $region = $form['region']->getData();
                if (!$code || !$name || !$region) {
                    throw new TransformationFailedException('Missing required fields');
                }

                return new Country($code, $name, $region);
            },
        ]);
    }
}
