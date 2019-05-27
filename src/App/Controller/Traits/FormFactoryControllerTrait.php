<?php

namespace App\Controller\Traits;

use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

trait FormFactoryControllerTrait
{
    /** @var FormFactoryInterface */
    protected $formFactory;

    /**
     * @param FormFactoryInterface $formFactory
     * @required
     */
    public function setFormFactory(FormFactoryInterface $formFactory): void
    {
        $this->formFactory = $formFactory;
    }

    /**
     * @param mixed $type
     * @param mixed|null  $data
     * @param array $options
     *
     * @return FormInterface
     */
    public function createForm($type, $data = null, array $options = []): FormInterface
    {
        return $this->formFactory->create($type, $data, $options);
    }

    public function createNamedForm($name, $type = FormType::class, $data = null, array $options = []): FormInterface
    {
        return $this->formFactory->createNamed($name, $type, $data, $options);
    }

    public function createFormBuilder(): FormBuilderInterface
    {
        return $this->formFactory->createBuilder();
    }
}
