<?php

namespace Paho\Vinuva\Validator;

/**
 * @Annotation
 */
class GreaterThanOrEqualProbable extends ProbableConstraint
{
    protected $comparison = self::GREATER_OR_EQUAL;
}
