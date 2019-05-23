<?php
declare(strict_types=1);

namespace App\Form\Admin\Hospital;

use App\Form\Types\CountryType;
use Paho\Vinuva\Models\Hospital;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, ['required' => true])
            ->add('short', TextType::class, ['required' => false])
            ->add('local', TextType::class, ['required' => false])
            ->add('country', CountryType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Hospital::class,
            'empty_data' => false,
        ]);
    }
}
