<?php

/*
 * This file is part of the JungiThemeBundle package.
 *
 * (c) Piotr Kugla <piku235@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jungi\Bundle\ThemeBundle\Tests\Fixtures\Validation;

use Jungi\Bundle\ThemeBundle\Resolver\Investigator\ThemeResolverInvestigatorInterface;
use Jungi\Bundle\ThemeBundle\Resolver\ThemeResolverInterface;

/**
 * The simple logic theme resolver investigator
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class LogicThemeResolverInvestigator implements ThemeResolverInvestigatorInterface
{
    private $suspect;

    public function __construct($pass)
    {
        $this->suspect = $pass;
    }

    public function setSuspect($pass)
    {
        $this->suspect = $pass;
    }

    public function isSuspect(ThemeResolverInterface $resolver)
    {
        return $this->suspect;
    }
}
