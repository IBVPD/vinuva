<?php

namespace App\Form\Rotavirus;

use App\Form\Common\ProbableType;
use NS\ColorAdminBundle\Form\Type\DatepickerType;
use Paho\Vinuva\Models\Rotavirus;
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
            ->add('under5', IntegerType::class, ['label' => 'No. hospitalizations in children under 5 years'])
            ->add('under5With', IntegerType::class, ['label' => 'No. of hospitalizations with diarrhea in children under 5 years'])
            ->add('suspected', IntegerType::class,['label'=> 'No. of children under 5 years who meet the criteria of suspect case'])
            ->add('withFormAndSample', ProbableType::class, ['label' => 'No. of children with forms and stool samples collected'])
            ->add('positiveUnder12', VaccinationType::class)
            ->add('positiveUnder23', VaccinationType::class)
            ->add('positiveUnder59', VaccinationType::class)
            ->add('positiveTotal', VaccinationType::class)
            ->add('deathsUnder12', VaccinationType::class)
            ->add('deathsUnder23', VaccinationType::class)
            ->add('deathsUnder59', VaccinationType::class)
            ->add('deathsTotal', VaccinationType::class)
            ->add('notifierComments', TextareaType::class, ['required' => false])
            ->add('verifierComments', TextareaType::class, ['required' => false])
            ->add('verificationDate', DatepickerType::class, ['required' => false]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Rotavirus::class
        ]);
    }
}
