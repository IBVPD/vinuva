<?php declare(strict_types=1);

namespace App\Form\Rotavirus;

use Paho\Vinuva\Models\Rotavirus\Vaccination;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Traversable;

class VaccinationType extends AbstractType implements DataMapperInterface
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $defaultOptions = $options['include_override'] ? [
            'required' => false,
            'label_attr' => ['class' => 'col-md-4 col-form-label'],
            'attr' => ['class' => 'form-control m-b-5 col-3'],
        ] : [
            'required' => false,
            'wrapper_class' => 'col-md-7',
            'label_attr' => ['class' => 'col-md-5 col-form-label'],
            'attr' => ['class' => 'form-control m-b-5 col-12'],
        ];

        $builder
            ->add('vaccinated', IntegerType::class, $defaultOptions)
            ->add('notVaccinated', IntegerType::class, $defaultOptions)
            ->add('noInformation', IntegerType::class, $defaultOptions);

        if ($options['include_override']) {
            $defaultOptions = ['required' => false, 'label' => 'Override', 'label_attr' => ['class' => 'col-md-4 col-form-label'],'wrapper_class'=>'col-md-8'];
            $builder
                ->add('vaccinatedOverride', CheckboxType::class, $defaultOptions)
                ->add('notVaccinatedOverride', CheckboxType::class, $defaultOptions)
                ->add('noInformationOverride', CheckboxType::class, $defaultOptions);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'required' => false,
            'data_class' => Vaccination::class,
            'empty_data' => false,
            'invalid_message' => 'Missing Required Fields',
            'error_bubbling' => false,
            'label_attr' => ['class' => 'col-form-label'],
            'include_override' => false,
        ]);
    }

    /**
     * @param Vaccination|null            $viewData
     * @param FormInterface[]|Traversable $forms
     */
    public function mapDataToForms($viewData, $forms): void
    {
        if ($viewData) {
            /** @var FormInterface[] $formData */
            $formData = iterator_to_array($forms);

            $formData['vaccinated']->setData($viewData->getVaccinated());
            $formData['notVaccinated']->setData($viewData->getNotVaccinated());
            $formData['noInformation']->setData($viewData->getNoInformation());
        }
    }

    /**
     * @param FormInterface[]|Traversable $forms
     * @param Vaccination|null            $viewData
     */
    public function mapFormsToData($forms, &$viewData): void
    {
        /** @var FormInterface[] $formData */
        $formData = iterator_to_array($forms);
        $vac      = $formData['vaccinated']->getData();
        $noVac    = $formData['notVaccinated']->getData();
        $noInfo   = $formData['noInformation']->getData();
        $viewData = new Vaccination($vac, $noVac, $noInfo);
    }
}
