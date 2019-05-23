<?php
declare(strict_types=1);

namespace App\Form\Admin\Region;

use App\Form\Types\CountryType;
use App\Form\Types\RoleType;
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
        $builder->add('name', TextType::class, ['constraints' => [new NotBlank()]]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Region::class,
            'invalid_message' => 'Missing required name field',
            'empty_data' => static function (FormInterface $form) {
                $name = $form['name']->getData();
                if (!$name) {
                    throw new TransformationFailedException('Missing required name');
                }

                return new Region($name);
            },
        ]);
    }
}
