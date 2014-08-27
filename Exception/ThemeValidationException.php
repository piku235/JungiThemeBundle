<?php

/*
 * This file is part of the JungiThemeBundle package.
 *
 * (c) Piotr Kugla <piku235@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jungi\Bundle\ThemeBundle\Exception;

use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * ThemeValidationException
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class ThemeValidationException extends \LogicException
{
    /**
     * @var ConstraintViolationListInterface
     */
    protected $violations;

    /**
     * Constructor
     *
     * @param string                           $message    A message
     * @param ConstraintViolationListInterface $violations Violations
     * @param int                              $code       A code (optional)
     * @param \Exception                       $previous   A previous exception (optional)
     */
    public function __construct($message, ConstraintViolationListInterface $violations, $code = null, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);

        $this->violations = $violations;
    }

    /**
     * Returns the errors
     *
     * @return array
     */
    public function getViolations()
    {
        return $this->violations;
    }
}
