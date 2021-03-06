<?php
declare(strict_types=1);

namespace App\Form\Admin\User;

use App\Form\Types\CountryType;
use App\Form\Types\HospitalType;
use App\Form\Types\RoleType;
use Paho\Vinuva\Models\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContext;

class EditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('active', CheckboxType::class, [
                'required' => false,
                'label' => 'Active Account?'
            ])
            ->add('name', TextType::class, [
                'required' => true,
                'label' => 'Name',
            ])
            ->add('login', TextType::class, [
                'required' => true,
                'label' => 'Login',
            ])
            ->add('email', EmailType::class, [
                'required' => true,
                'label' => 'Email',
            ])
            ->add('role', RoleType::class, [
                'required' => true,
                'label' => 'Role',
            ])
            ->add('country', CountryType::class, [
                'label' => 'Country',
                'error_bubbling' => false,
            ])
            ->add('hospitals', HospitalType::class, [
                'label' => 'Hospitals',
                'required' => false,
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('plainPassword', RepeatedType::class, [
                'required' => false,
                'type' => PasswordType::class,
                'first_options' => ['label' => 'Password'],
                'second_options' => ['label' => 'Re-enter Password'],
            ])
            ->add('phone', TextType::class, [
                'required' => false,
                'label' => 'Phone',
            ])
            ->add('address', TextType::class, [
                'required' => false,
                'label' => 'Address',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'empty_data' => false,
            'constraints' => [new Callback([$this, 'validate'])],
        ]);
    }

    public function validate($data, ExecutionContext $context): void
    {
        if ($data instanceof User && $data->getRole() !== User::ROLE_ADMIN && !$data->getCountry()) {
            $context->buildViolation('This field is required for non-administrators')->atPath('[country]')->addViolation();
        }
    }
}
