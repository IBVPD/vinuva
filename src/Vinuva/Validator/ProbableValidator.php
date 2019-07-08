<?php


namespace Paho\Vinuva\Validator;

use Paho\Vinuva\Models\Common\Probable;
use Symfony\Component\PropertyAccess\Exception\NoSuchPropertyException;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\ConstraintDefinitionException;

class ProbableValidator extends ConstraintValidator
{
    /** @var PropertyAccessorInterface */
    private $propertyAccessor;

    public function __construct(PropertyAccessorInterface $propertyAccessor = null)
    {
        $this->propertyAccessor = $propertyAccessor;
    }

    /**
     * @param mixed                         $value
     * @param ProbableConstraint|Constraint $constraint
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$value instanceof Probable) {
            throw new \InvalidArgumentException("Expecting type Paho\\Vinuva\\Models\\Common\\Probable got {get_class($value)} instead");
        }

        if (null === $object = $this->context->getObject()) {
            return;
        }

        try {
            $compared      = false;
            $comparedValue = $this->propertyAccessor->getValue($object, $constraint->propertyPath);

            if ($comparedValue instanceof Probable) {
                $comparedValue = $comparedValue->getTotal();
            }

            switch ($constraint->getComparison()) {
                case ProbableConstraint::LESS:
                    $compared = $value->getTotal() < $comparedValue;
                    break;
                case ProbableConstraint::LESS_OR_EQUAL:
                    $compared = $value->getTotal() <= $comparedValue;
                    break;
                case ProbableConstraint::EQUAL:
                    $compared = $value->getTotal() === $comparedValue;
                    break;
                case ProbableConstraint::GREATER:
                    $compared = $value->getTotal() > $comparedValue;
                    break;
                case ProbableConstraint::GREATER_OR_EQUAL:
                    $compared = $value->getTotal() >= $comparedValue;
                    break;
            }

            if (!$compared) {
                $this->context
                    ->buildViolation($constraint->message)
                    ->atPath('total')
                    ->addViolation();
            }

        } catch (NoSuchPropertyException $e) {
            throw new ConstraintDefinitionException(sprintf('Invalid property path "%s" provided to "%s" constraint: %s', $constraint->propertyPath, \get_class($constraint), $e->getMessage()), 0, $e);
        }

    }
}
