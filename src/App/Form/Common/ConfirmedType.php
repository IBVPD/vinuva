<?php declare(strict_types=1);

namespace App\Form\Common;

use Paho\Vinuva\Models\Common\Confirmed;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\Exception;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Traversable;

class ConfirmedType extends AbstractType implements DataMapperInterface
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $defaultOptions = $options['include_override'] ? [
            'required' => false,
            'wrapper_class' => 'col-md-4',
            'label_attr' => ['class' => 'col-md-3 col-form-label'],
            'attr' => ['class' => 'form-control col-3'],
        ] : [
            'required' => false,
            'wrapper_class' => 'col-md-7',
            'label_attr' => ['class' => 'col-md-5 col-form-label'],
            'attr' => ['class' => 'form-control'],
        ];

        $builder
            ->add('hib', IntegerType::class, $defaultOptions)
            ->add('hi', IntegerType::class, $defaultOptions)
            ->add('nm', IntegerType::class, $defaultOptions)
            ->add('spn', IntegerType::class, $defaultOptions)
            ->add('other', IntegerType::class, $defaultOptions)
            ->add('contamination', IntegerType::class, $options['include_override'] ? ['required' => false, 'label_attr' => ['class' => 'col-md-4 col-form-label'], 'attr' => ['class' => 'form-control m-b-5 col-3']] : ['required' => false, 'wrapper_class' => 'col-md-6', 'label_attr' => ['class' => 'col-md-6 col-form-label'], 'attr' => ['class' => 'form-control m-b-5 col-12']]);

        if ($options['include_override']) {
            $defaultOptions = [
                'required' => false,
                'label' => 'Override',
                'mapped' => false,
                'label_attr' => ['class' => 'col-6 col-form-label'],
                'wrapper_class' => 'col-md-2',
            ];

            $builder
                ->add('hibOverride', CheckboxType::class, $defaultOptions)
                ->add('hiOverride', CheckboxType::class, $defaultOptions)
                ->add('nmOverride', CheckboxType::class, $defaultOptions)
                ->add('spnOverride', CheckboxType::class, $defaultOptions)
                ->add('otherOverride', CheckboxType::class, $defaultOptions)
                ->add('contaminationOverride', CheckboxType::class, $defaultOptions);
        }

        $builder->setDataMapper($this);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Confirmed::class,
            'empty_data' => false,
            'invalid_message' => 'All fields are required',
            'error_bubbling' => false,
            'label_attr' => ['class' => 'col-form-label'],
            'include_override' => false,
        ]);
    }

    /**
     * @param Confirmed|null              $viewData View data of the compound form being initialized
     * @param FormInterface[]|Traversable $forms    A list of {@link FormInterface} instances
     *
     * @throws Exception\UnexpectedTypeException if the type of the data parameter is not supported
     */
    public function mapDataToForms($viewData, $forms): void
    {
        if ($viewData) {
            /** @var FormInterface[] $formArray */
            $formArray = iterator_to_array($forms);
            $formArray['hib']->setData($viewData->getHib());
            $formArray['hi']->setData($viewData->getHi());
            $formArray['nm']->setData($viewData->getNm());
            $formArray['spn']->setData($viewData->getSpn());
            $formArray['other']->setData($viewData->getOther());
            $formArray['contamination']->setData($viewData->getContamination());
        }
    }

    /**
     * @param FormInterface[]|Traversable $forms     A list of {@link FormInterface} instances
     * @param Confirmed|null              $viewData  The compound form's view data that get mapped
     *                                               its children model data
     *
     * @throws Exception\UnexpectedTypeException if the type of the data parameter is not supported
     */
    public function mapFormsToData($forms, &$viewData): void
    {
        /** @var FormInterface[] $formData */
        $formData    = iterator_to_array($forms);
        $hib         = $formData['hib']->getData();
        $hi          = $formData['hi']->getData();
        $spn         = $formData['spn']->getData();
        $nm          = $formData['nm']->getData();
        $other       = $formData['other']->getData();
        $contaminant = $formData['contamination']->getData();
        $viewData    = new Confirmed($hib, $hi, $nm, $spn, $other, $contaminant);
    }
}
