<?php
declare(strict_types=1);

namespace App\Form\Common;

use Paho\Vinuva\Models\Common\Probable;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Traversable;

class ProbableType extends AbstractType implements DataMapperInterface
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('under12', IntegerType::class, ['label' => 'Under 12 months', 'required' => false, 'label_attr' => ['class' => 'col-form-label']])
            ->add('under23', IntegerType::class, ['label' => '12 - 23 months', 'required' => false, 'label_attr' => ['class' => 'col-form-label']])
            ->add('under59', IntegerType::class, ['label' => '24 - 59 months', 'required' => false, 'label_attr' => ['class' => 'col-form-label']])
            ->add('total', IntegerType::class, ['label' => 'Total < 5', 'required' => false, 'label_attr' => ['class' => 'col-form-label']])
            ->add('totalOverride', CheckboxType::class, ['label' => 'Override Total', 'required' => false, 'mapped'=>false, 'label_attr' => ['class' => 'col-form-label']])
        ;

        $builder->setDataMapper($this);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Probable::class,
            'empty_data' => false,
            'invalid_message' => 'All fields are required',
            'error_bubbling' => false,
        ]);
    }

    /**
     * @param Probable|null               $viewData
     * @param FormInterface[]|Traversable $forms
     */
    public function mapDataToForms($viewData, $forms): void
    {
        if ($viewData) {
            $formArray = iterator_to_array($forms);
            $formArray['under12']->setData($viewData->get12());
            $formArray['under23']->setData($viewData->get23());
            $formArray['under59']->setData($viewData->get59());
            $formArray['total']->setData($viewData->getTotal());
        }
    }

    public function mapFormsToData($forms, &$viewData): void
    {
        $formData = iterator_to_array($forms);

        $_12      = $formData['under12']->getData();
        $_23      = $formData['under23']->getData();
        $_59      = $formData['under59']->getData();
        $total    = $formData['total']->getData();
        $viewData = new Probable($_12, $_23, $_59, $total);
    }
}
