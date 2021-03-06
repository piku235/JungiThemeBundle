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
 * Interface for changing the current theme for a request.
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
interface ThemeChangerInterface
{
    /**
     * Changes the current theme with the new one.
     *
     * @param string|ThemeInterface $theme   A theme name or a theme instance
     * @param Request               $request A Request instance
     */
    public function change($theme, Request $request);
}
