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
 * The SessionThemeResolver uses session mechanism for storing the theme name.
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class SessionThemeResolver implements ThemeResolverInterface
{
    /**
     * @var string
     */
    const SESSION_NAME = '_jungi_theme';

    /**
     * {@inheritdoc}
     */
    public function resolveThemeName(Request $request)
    {
        if (!$request->hasSession()) {
            return;
        }

        return $request->getSession()->get(self::SESSION_NAME);
    }

    /**
     * {@inheritdoc}
     */
    public function setThemeName($themeName, Request $request)
    {
        if (!$request->hasSession()) {
            return;
        }

        $request->getSession()->set(self::SESSION_NAME, $themeName);
    }
}
