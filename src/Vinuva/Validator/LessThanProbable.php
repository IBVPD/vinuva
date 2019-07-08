<?php

namespace Paho\Vinuva\Validator;

/**
 * @Annotation
 */
class LessThanProbable extends ProbableConstraint
{
    protected $comparison = self::LESS;
}
