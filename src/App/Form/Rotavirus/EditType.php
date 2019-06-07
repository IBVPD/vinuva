<?php

namespace App\Form\Rotavirus;

use App\Form\Common\ProbableType;
use NS\ColorAdminBundle\Form\Type\DatepickerType;
use Paho\Vinuva\Models\Rotavirus;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class EditType extends AbstractType
{
    /** @var AuthorizationCheckerInterface */
    private $authChecker;

    public function __construct(AuthorizationCheckerInterface $authChecker)
    {
        $this->authChecker = $authChecker;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('under5', IntegerType::class, ['label' => 'No. hospitalizations in children under 5 years'])
            ->add('under5With', IntegerType::class, ['label' => 'No. of hospitalizations with diarrhea in children under 5 years'])
            ->add('suspected', IntegerType::class,['label'=> 'No. of children under 5 years who meet the criteria of suspect case'])
            ->add('withFormAndSample', ProbableType::class, ['label' => 'No. of children with forms and stool samples collected'])
            ->add('positiveUnder12', VaccinationType::class, ['label' => 'Under 12 months'])
            ->add('positiveUnder23', VaccinationType::class, ['label' => '12 - 23 months'])
            ->add('positiveUnder59', VaccinationType::class, ['label' => '24-59 months'])
            ->add('positiveTotal', VaccinationType::class, ['label' => 'Total < 5'])
            ->add('deathsUnder12', VaccinationType::class, ['label' => 'Under 12 months'])
            ->add('deathsUnder23', VaccinationType::class, ['label' => '12-23 months'])
            ->add('deathsUnder59', VaccinationType::class, ['label' => '24-59 months'])
            ->add('deathsTotal', VaccinationType::class, ['label' => 'Total < 5'])
            ->add('notifierComments', TextareaType::class, ['required' => false]);

        if ($this->authChecker->isGranted('ROLE_VERIFIER')) {
            $builder
                ->add('verified', CheckboxType::class, ['required' => false])
                ->add('verifierComments', TextareaType::class, ['required' => false])
                ->add('verificationDate', DatepickerType::class, ['required' => false]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Rotavirus::class
        ]);
    }
}
