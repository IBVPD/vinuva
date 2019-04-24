<?php

namespace App\Form\Meningitis;

use App\Form\Common\ConfirmedType;
use App\Form\Common\ProbableType;
use App\Form\Common\DeathCountType;
use NS\ColorAdminBundle\Form\Type\DatepickerType;
use Paho\Vinuva\Models\Meningitis;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('under5', IntegerType::class)
            ->add('suspected', IntegerType::class)
            ->add('probable', ProbableType::class, ['label' => 'No. of probable cases of meningitis'])
            ->add('under12Confirmed', ConfirmedType::class)
            ->add('under23Confirmed', ConfirmedType::class)
            ->add('under59Confirmed', ConfirmedType::class)
            ->add('totalConfirmed', ConfirmedType::class)
            ->add('numberOfDeaths', DeathCountType::class)
            ->add('notifierComments', TextareaType::class, ['required' => false])
            ->add('verifierComments', TextareaType::class, ['required' => false])
            ->add('verificationDate', DatepickerType::class, ['required' => false]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Meningitis::class
        ]);
    }
}
