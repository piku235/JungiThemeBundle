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
use Symfony\Component\HttpFoundation\Response;

/**
 * The class handles reading/writing theme names using php sessions
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
     * (non-PHPdoc)
     * @see \Jungi\Bundle\ThemeBundle\Resolver\ThemeResolverInterface::resolve()
     */
    public function resolveThemeName(Request $request)
    {
        if (!$request->hasSession()) {
            return null;
        }

        return $request->getSession()->get(self::SESSION_NAME);
    }

    /**
     * (non-PHPdoc)
     * @see \Jungi\Bundle\ThemeBundle\Resolver\ThemeResolverInterface::setTheme()
     */
    public function setThemeName($themeName, Request $request)
    {
        if (!$request->hasSession()) {
            return;
        }

        $request->getSession()->set(self::SESSION_NAME, $themeName);
    }
}