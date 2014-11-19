<?php

/*
 * This file is part of the JungiThemeBundle package.
 *
 * (c) Piotr Kugla <piku235@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jungi\Bundle\ThemeBundle\Validation;

use Symfony\Component\Validator\ConstraintViolationListInterface;
use Jungi\Bundle\ThemeBundle\Exception\ThemeValidationException;

/**
 * The class provides useful utilities for the validation
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class ValidationUtils
{
    /**
     * Returns the well formatted ThemeValidationException
     *
     * @param string                           $message    A message
     * @param ConstraintViolationListInterface $violations Violations
     *
     * @return ThemeValidationException
     */
    public static function validationException($message, ConstraintViolationListInterface $violations)
    {
        return new ThemeValidationException(static::formatValidationMessage($message, $violations), $violations);
    }

    /**
     * Returns the well formatted validation message
     *
     * @param string                           $message    A message
     * @param ConstraintViolationListInterface $violations Violations
     *
     * @return string
     */
    public static function formatValidationMessage($message, ConstraintViolationListInterface $violations)
    {
        $message = rtrim($message, '. ').'.';
        foreach ($violations as $violation) {
            $message .= $violation->getPropertyPath()
                ? sprintf(' Property %s: %s', $violation->getPropertyPath(), $violation->getMessage())
                : ' '.$violation->getMessage();
        }

        return $message;
    }
}
