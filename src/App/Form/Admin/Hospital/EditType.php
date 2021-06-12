<?php
declare(strict_types=1);

namespace App\Form\Admin\Hospital;

use App\Form\Types\CountryType;
use Paho\Vinuva\Models\Hospital;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class EditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'required' => true,
                'label' => 'Name',
                'constraints' => [new NotBlank()]
            ])
            ->add('short', TextType::class, [
                'required' => true,
                'label' => 'Short Name',
                'constraints' => [new NotBlank()]
            ])
            ->add('local', TextType::class, [
                'required' => true,
                'label' => 'Local Name',
                'constraints' => [new NotBlank()]
            ])
            ->add('active', CheckboxType::class, [
                'required' => false,
                'label' => 'Active',
            ])
            ->add('country', CountryType::class, ['required' => true]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Hospital::class,
            'empty_data' => false,
        ]);
    }
}
