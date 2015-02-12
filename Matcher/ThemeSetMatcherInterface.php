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
use Symfony\Component\HttpFoundation\Request;

/**
 * All implementations are responsible for matching an appropriate theme instance based on
 * a given theme set
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
interface ThemeSetMatcherInterface
{
    /**
     * Matches an appropriate theme from a given theme set
     *
     * @param ThemeInterface[] $themes  Themes
     * @param Request          $request A Request instance
     *
     * @return ThemeInterface
     */
    public function match(array $themes, Request $request);
}
