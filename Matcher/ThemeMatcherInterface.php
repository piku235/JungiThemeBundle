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
use Jungi\Bundle\ThemeBundle\Core\ThemeNameReferenceInterface;
use Jungi\Bundle\ThemeBundle\Exception\NotSupportedException;
use Jungi\Bundle\ThemeBundle\Exception\ThemeNotFoundException;
use Symfony\Component\HttpFoundation\Request;

/**
 * All implementations are responsible for matching a theme instance based on a given
 * theme name.
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
interface ThemeMatcherInterface
{
    /**
     * Checks whether a given theme name is supported by the matcher
     *
     * @param string $themeName A theme name
     *
     * @return bool
     */
    public function supports($themeName);

    /**
     * Matches an appropriate theme based on a given theme name for a given Request
     *
     * A Request instance is an addition which can be used for different goals
     *
     * @param string|ThemeNameReferenceInterface $themeName A theme name
     * @param Request                            $request   A Request instance
     *
     * @return ThemeInterface
     *
     * @throws ThemeNotFoundException If none matched
     */
    public function match($themeName, Request $request);
}
