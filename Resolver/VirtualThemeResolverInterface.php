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

use Jungi\Bundle\ThemeBundle\Core\ThemeInterface;
use Jungi\Bundle\ThemeBundle\Core\VirtualThemeInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Interface for resolving an appropriate theme for the given virtual theme and the request.
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
interface VirtualThemeResolverInterface
{
    /**
     * Resolves an appropriate theme for the given virtual theme.
     *
     * @param VirtualThemeInterface $theme   A virtual theme
     * @param Request               $request A Request instance
     *
     * @return ThemeInterface
     */
    public function resolveTheme(VirtualThemeInterface $theme, Request $request);
}
