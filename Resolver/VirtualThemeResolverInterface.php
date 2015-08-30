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
 * Interface for resolving a suitable theme from sub themes of the given
 * virtual theme.
 *
 * It is used to determine the pointed theme of a virtual theme.
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
interface VirtualThemeResolverInterface
{
    /**
     * Resolves a suitable theme for the given virtual theme.
     *
     * @param VirtualThemeInterface $theme   A virtual theme
     * @param Request               $request A Request instance
     *
     * @return ThemeInterface
     */
    public function resolveTheme(VirtualThemeInterface $theme, Request $request);
}
