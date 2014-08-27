<?php

/*
 * This file is part of the JungiThemeBundle package.
 *
 * (c) Piotr Kugla <piku235@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jungi\Bundle\ThemeBundle\Resolver\Investigator;

use Jungi\Bundle\ThemeBundle\Resolver\ThemeResolverInterface;

/**
 * Implemented classes allows for check if a given theme resolver is suspected
 * and something special should be done with it
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
interface ThemeResolverInvestigatorInterface
{
    /**
     * Checks if a given theme resolver is suspect
     *
     * @param ThemeResolverInterface $resolver A theme resolver
     *
     * @return boolean
     */
    public function isSuspect(ThemeResolverInterface $resolver);
}
