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

/**
 * ThemeNotFoundException
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class ThemeNotFoundException extends \RuntimeException
{
    /**
     * @var string
     */
    private $theme;

    /**
     * Constructor
     *
     * @param string $theme The not found theme name
     * @param string                             $message  A message (optional)
     * @param int                                $code     A code (optional)
     * @param \Exception                         $previous The previous exception (optional)
     */
    public function __construct($theme, $message = null, $code = 0, \Exception $previous = null)
    {
        $this->theme = $theme;
        if (null === $message) {
            $message = sprintf('The theme "%s" can not be found.', $theme);
        }

        parent::__construct($message, $code, $previous);
    }

    /**
     * Returns the not found theme name
     *
     * @return string
     */
    public function getThemeName()
    {
        return $this->theme;
    }
}
