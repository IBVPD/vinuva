<?php

namespace App\Form\Pneumonia;

use App\Form\Common\ConfirmedType;
use App\Form\Common\DeathCountType;
use App\Form\Common\ProbableType;
use NS\ColorAdminBundle\Form\Type\DatepickerType;
use Paho\Vinuva\Models\Pneumonia;
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
            ->add('suspected', IntegerType::class, ['label' => 'No. suspected pneumonia'])
            ->add('suspectedWith', IntegerType::class, ['label' => 'Number of suspected pneumonia cases with x-ray and forms'])
            ->add('probable', ProbableType::class, ['label' => 'No. of probable cases of pneumonia'])
            ->add('probableWithBlood', ProbableType::class, ['label' => 'No. of probable cases with blood specimen'])
            ->add('probableWithPleural', ProbableType::class, ['label' => 'No. of probable cases with pleural fluid'])
            ->add('under12Confirmed', ConfirmedType::class, ['label' => 'Under 12 months'])
            ->add('under23Confirmed', ConfirmedType::class, ['label' => '12 - 23 months'])
            ->add('under59Confirmed', ConfirmedType::class, ['label' => '24 - 59 months'])
            ->add('totalConfirmed', ConfirmedType::class, ['label' => 'Total < 5', 'include_override' => true])
            ->add('numberOfDeaths', DeathCountType::class)
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
            'data_class' => Pneumonia::class,
        ]);
    }
}
