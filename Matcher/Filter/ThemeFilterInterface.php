<?php

/*
 * This file is part of the JungiThemeBundle package.
 *
 * (c) Piotr Kugla <piku235@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jungi\Bundle\ThemeBundle\Matcher\Filter;

use Symfony\Component\HttpFoundation\Request;

/**
 * ThemeFilterInterface
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
interface ThemeFilterInterface
{
    /**
     * Filters a given theme collection by removing these themes that are not suitable'
     *
     * @param ThemeCollection $themes  A theme collection
     * @param Request         $request A Request instance
     *
     * @return void
     */
    public function filter(ThemeCollection $themes, Request $request);
}
