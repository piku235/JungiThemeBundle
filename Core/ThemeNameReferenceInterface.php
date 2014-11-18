<?php

/*
 * This file is part of the JungiThemeBundle package.
 *
 * (c) Piotr Kugla <piku235@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jungi\Bundle\ThemeBundle\Core;

/**
 * ThemeNameReferenceInterface
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
interface ThemeNameReferenceInterface
{
    /**
     * Returns the theme name
     *
     * @return string
     */
    public function getName();

    /**
     * Returns the string representation of the reference
     *
     * @return string
     */
    public function __toString();
}
