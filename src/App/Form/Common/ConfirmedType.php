<?php
declare(strict_types=1);

namespace App\Form\Common;

use Paho\Vinuva\Models\Common\Confirmed;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\Exception;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Traversable;

class ConfirmedType extends AbstractType implements DataMapperInterface
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('hib', IntegerType::class, ['required' => false, 'attr' => ['class' => 'form-control m-b-5 col-4']])
            ->add('hi', IntegerType::class, ['required' => false, 'attr' => ['class' => 'form-control m-b-5 col-4']])
            ->add('nm', IntegerType::class, ['required' => false, 'attr' => ['class' => 'form-control m-b-5 col-4']])
            ->add('spn', IntegerType::class, ['required' => false, 'attr' => ['class' => 'form-control m-b-5 col-4']])
            ->add('other', IntegerType::class, ['required' => false, 'attr' => ['class' => 'form-control m-b-5 col-4']])
            ->add('contamination', IntegerType::class, ['required' => false, 'attr' => ['class' => 'form-control m-b-5 col-4']]);

        $builder->setDataMapper($this);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Confirmed::class,
            'empty_data' => false,
            'invalid_message' => 'All fields are required',
            'error_bubbling' => false,
        ]);
    }

    /**
     * @param Confirmed|null              $viewData View data of the compound form being initialized
     * @param FormInterface[]|Traversable $forms    A list of {@link FormInterface} instances
     *
     * @throws Exception\UnexpectedTypeException if the type of the data parameter is not supported
     */
    public function mapDataToForms($viewData, $forms)
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
    public function mapFormsToData($forms, &$viewData)
    {
        /** @var FormInterface[] $formData */
        $formData    = iterator_to_array($forms);
        $hib         = $formData['hib']->getData();
        $hi          = $formData['hi']->getData();
        $spn         = $formData['spn']->getData();
        $nm          = $formData['nm']->getData();
        $other       = $formData['other']->getData();
        $contaminant = $formData['contaminant']->getData();
        $viewData    = new Confirmed($hib, $hi, $nm, $spn, $other, $contaminant);
    }
}
