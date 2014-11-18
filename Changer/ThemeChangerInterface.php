<?php

/*
 * This file is part of the JungiThemeBundle package.
 *
 * (c) Piotr Kugla <piku235@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jungi\Bundle\ThemeBundle\Changer;

use Jungi\Bundle\ThemeBundle\Core\ThemeInterface;
use Jungi\Bundle\ThemeBundle\Core\ThemeNameReferenceInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * ThemeChangerInterface allows for change the current theme for the request
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
interface ThemeChangerInterface
{
    /**
     * Changes the current theme with a new one
     *
     * @param string|ThemeNameReferenceInterface|ThemeInterface $themeName A theme name, a theme instance or
     *                                                                     a theme name reference
     * @param Request                                           $request   A Request instance
     *
     * @return void
     */
    public function change($themeName, Request $request);
}
