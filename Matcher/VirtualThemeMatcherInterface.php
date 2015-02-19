<?php

/*
 * This file is part of the JungiThemeBundle package.
 *
 * (c) Piotr Kugla <piku235@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jungi\Bundle\ThemeBundle\Matcher;

use Jungi\Bundle\ThemeBundle\Core\ThemeInterface;
use Jungi\Bundle\ThemeBundle\Core\VirtualThemeInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * All implementations are responsible for matching an appropriate theme instance based on
 * a given virtual theme
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
interface VirtualThemeMatcherInterface
{
    /**
     * Matches an appropriate theme for a given virtual theme
     *
     * @param VirtualThemeInterface $theme A virtual theme
     * @param Request          $request A Request instance
     *
     * @return ThemeInterface
     */
    public function match(VirtualThemeInterface $theme, Request $request);
}
