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
            ->add('suspectedWith', IntegerType::class, ['label' => 'No. suspected meningitis with CSF2 and forms'])
            ->add('probable', ProbableType::class, ['label' => 'No. of probable cases of meningitis'])
            ->add('under12Confirmed', ConfirmedType::class)
            ->add('under23Confirmed', ConfirmedType::class)
            ->add('under59Confirmed', ConfirmedType::class)
            ->add('totalConfirmed', ConfirmedType::class)
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
            'data_class' => Meningitis::class,
        ]);
    }
}
