<?php

/*
 * This file is part of the JungiThemeBundle package.
 *
 * (c) Piotr Kugla <piku235@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jungi\Bundle\ThemeBundle\Tests\Resolver\Investigator;

use Jungi\Bundle\ThemeBundle\Resolver\Investigator\ThemeResolverInvestigator;
use Jungi\Bundle\ThemeBundle\Tests\TestCase;
use Jungi\Bundle\ThemeBundle\Resolver\SessionThemeResolver;
use Jungi\Bundle\ThemeBundle\Resolver\InMemoryThemeResolver;

/**
 * ThemeResolverInvestigator Test Case
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class ThemeResolverInvestigatorTest extends TestCase
{
    /**
     * @dataProvider getThemeResolvers
     */
    public function testSuspectResolvers($themeResolver)
    {
        $investigator = new ThemeResolverInvestigator();
        $investigator->add('Jungi\Bundle\ThemeBundle\Resolver\SessionThemeResolver');
        $investigator->add(new InMemoryThemeResolver());

        $this->assertTrue($investigator->isSuspect($themeResolver));
    }

    /**
     * @dataProvider getThemeResolvers
     */
    public function testTrustedResolvers($themeResolver)
    {
        $investigator = new ThemeResolverInvestigator();
        $investigator->add('Jungi\Bundle\ThemeBundle\Resolver\CookieThemeResolver');

        $this->assertFalse($investigator->isSuspect($themeResolver));
    }

    /**
     * Data provider
     *
     * @return array
     */
    public function getThemeResolvers()
    {
        return array(
            array(new SessionThemeResolver()),
            array(new InMemoryThemeResolver())
        );
    }
}
