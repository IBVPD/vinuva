<?php

namespace Paho\Vinuva\Validator;

/**
 * @Annotation
 */
class LessThanOrEqualProbable extends ProbableConstraint
{
    protected $comparison = self::LESS_OR_EQUAL;
}
