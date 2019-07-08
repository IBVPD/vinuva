<?php

namespace Paho\Vinuva\Validator;

/**
 * @Annotation
 */
class GreaterThanProbable extends ProbableConstraint
{
    protected $comparison = self::GREATER;
}
