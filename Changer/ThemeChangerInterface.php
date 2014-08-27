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
use Symfony\Component\HttpFoundation\Request;

/**
 * ThemeChangerInterface gives the possibility of change the current theme located in a given Request instance.
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
interface ThemeChangerInterface
{
    /**
     * Changes the current theme with a new one
     *
     * @param string|ThemeInterface $theme   A theme name or a ThemeInterface instance
     * @param Request               $request A Request instance
     *
     * @return void
     */
    public function change($theme, Request $request);
}
