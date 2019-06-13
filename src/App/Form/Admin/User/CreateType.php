<?php
declare(strict_types=1);

namespace App\Form\Admin\User;

use App\Form\Types\CountryType;
use App\Form\Types\RoleType;
use Paho\Vinuva\Models\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Context\ExecutionContext;

class CreateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class)
            ->add('email', EmailType::class)
            ->add('role', RoleType::class, ['constraints' => [new NotBlank()]])
            ->add('country', CountryType::class, ['required' => false])
            ->add('phone', TextType::class, ['required' => false])
            ->add('address', TextType::class, ['required' => false]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'constraints' => [new Callback([$this, 'validate'])],
        ]);
    }

    public function validate($data, ExecutionContext $context): void
    {
        if ($data['role'] !== User::ROLE_ADMIN && empty($data['country'])) {
            $context->buildViolation('This field is required for non-administrators')->atPath('country')->addViolation();
        }
    }
}
