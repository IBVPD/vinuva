<?php
declare(strict_types=1);

namespace App\Form\Admin\Hospital;

use App\Form\Types\CountryType;
use App\Form\Types\RoleType;
use Paho\Vinuva\Models\Hospital;
use Paho\Vinuva\Models\Region;
use Paho\Vinuva\Models\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Context\ExecutionContext;

class CreateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, ['constraints' => [new NotBlank()]])
            ->add('short', TextType::class, ['required' => false])
            ->add('local', TextType::class, ['required' => false])
            ->add('country', CountryType::class, ['required' => true]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Region::class,
            'invalid_message' => 'Missing required fields',
            'empty_data' => static function (FormInterface $form) {
                $name = $form['name']->getData();
                $country = $form['country']->getData();
                if (!$name || !$country) {
                    throw new TransformationFailedException('Missing required fields');
                }

                return new Hospital($name, $country);
            },
        ]);
    }
}
