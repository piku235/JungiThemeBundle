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
 * The implemented classes are responsible for determining a theme name for a particular request.
 *
 * Theme resolvers also allows for altering the stored theme name in a request.
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
interface ThemeResolverInterface
{
    /**
     * Returns the appropriate theme name for the given request.
     *
     * @param Request $request A request instance
     *
     * @return string|null Returns null if a theme name is not set
     */
    public function resolveThemeName(Request $request);

    /**
     * Sets the theme for the given request.
     *
     * @param string  $themeName The theme name
     * @param Request $request   A request instance
     */
    public function setThemeName($themeName, Request $request);
}
