<?php

/*
 * This file is part of the JungiThemeBundle package.
 *
 * (c) Piotr Kugla <piku235@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jungi\Bundle\ThemeBundle\Selector;

use Jungi\Bundle\ThemeBundle\Selector\Exception\NullThemeException;
use Symfony\Component\HttpFoundation\Request;

/**
 * ThemeSelectorInterface determines which theme will be used for a given Request.
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
interface ThemeSelectorInterface
{
    /**
     * Selects an appropriate theme for a given Request.
     *
     * @param Request $request A Request instance
     *
     * @return \Jungi\Bundle\ThemeBundle\Core\ThemeInterface
     *
     * @throws NullThemeException If there is no any matching theme for the request
     */
    public function select(Request $request);
}
