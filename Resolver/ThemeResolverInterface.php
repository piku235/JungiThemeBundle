<?php

/*
 * This file is part of the JungiThemeBundle package.
 *
 * (c) Piotr Kugla <piku235@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jungi\Bundle\ThemeBundle\Resolver;

use Symfony\Component\HttpFoundation\Request;

/**
 * Interface for determining the current theme for a particular request and also
 * this interface allows for altering the current theme.
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
interface ThemeResolverInterface
{
    /**
     * Returns the current theme name for the given request.
     *
     * @param Request $request A request instance
     *
     * @return string|null Returns null if the current theme name
     *                     can not be resolved
     */
    public function resolveThemeName(Request $request);

    /**
     * Sets the current theme for the given request.
     *
     * @param string  $themeName A theme name
     * @param Request $request   A request instance
     */
    public function setThemeName($themeName, Request $request);
}
