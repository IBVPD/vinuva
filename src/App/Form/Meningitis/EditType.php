<?php

namespace App\Form\Meningitis;

use App\Form\Common\ConfirmedType;
use App\Form\Common\DeathCountType;
use App\Form\Common\ProbableType;
use NS\ColorAdminBundle\Form\Type\DatepickerType;
use Paho\Vinuva\Models\Meningitis;
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
            ->add('under5', IntegerType::class, ['label' => 'No. of child hospitalizations under 5 years'])
            ->add('suspected', IntegerType::class, ['label' => 'No. of suspected cases of meningitis'])
            ->add('suspectedWith', IntegerType::class, ['label' => 'No. suspected meningitis with CSF and forms'])
            ->add('probable', ProbableType::class, ['label' => 'No. of probable cases of meningitis'])
            ->add('under12Confirmed', ConfirmedType::class, ['label' => 'Under 12 months'])
            ->add('under23Confirmed', ConfirmedType::class, ['label' => '12 - 23 months'])
            ->add('under59Confirmed', ConfirmedType::class, ['label' => '24 - 59 months'])
            ->add('totalConfirmed', ConfirmedType::class, ['label' => 'Total < 5', 'include_override' => true])
            ->add('numberOfDeaths', DeathCountType::class,['label' => 'Number of deaths'])
            ->add('notifierComments', TextareaType::class, ['required' => false]);

        if ($this->authChecker->isGranted('ROLE_VERIFIER')) {
            $builder
                ->add('verified', CheckboxType::class, ['required' => false, 'label' => 'Verified?'])
                ->add('verifierComments', TextareaType::class, ['required' => false, 'label' => 'Verifier Comments'])
                ->add('verificationDate', DatepickerType::class, ['required' => false, 'label' => 'Verification Date']);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Meningitis::class,
        ]);
    }
}
