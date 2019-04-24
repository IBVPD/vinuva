<?php
declare(strict_types=1);

namespace App\Form\Rotavirus;

use Paho\Vinuva\Models\Rotavirus\Vaccination;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Traversable;

class VaccinationType extends AbstractType implements DataMapperInterface
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('vaccinated', IntegerType::class, ['required' => false, 'attr' => ['class' => 'form-control m-b-5 col-4']])
            ->add('notVaccinated', IntegerType::class, ['required' => false, 'attr' => ['class' => 'form-control m-b-5 col-4']])
            ->add('noInformation', IntegerType::class, ['required' => false, 'attr' => ['class' => 'form-control m-b-5 col-4']]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Vaccination::class,
            'empty_data' => false,
            'invalid_message' => 'Missing Required Fields',
            'error_bubbling' => false,
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
