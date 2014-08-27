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
     * @var ThemeResolverInvestigator
     */
    protected $investigator;

    /**
     * (non-PHPdoc)
     * @see PHPUnit_Framework_TestCase::setUp()
     */
    protected function setUp()
    {
        $this->investigator = new ThemeResolverInvestigator();
    }

    /**
     * (non-PHPdoc)
     * @see PHPUnit_Framework_TestCase::tearDown()
     */
    protected function tearDown()
    {
        $this->investigator = null;
    }

    /**
     * @dataProvider getThemeResolvers
     */
    public function testSuspectResolvers($themeResolver)
    {
        $this->investigator->add('Jungi\Bundle\ThemeBundle\Resolver\SessionThemeResolver');
        $this->investigator->add(new InMemoryThemeResolver());

        $this->assertTrue($this->investigator->isSuspect($themeResolver));
    }

    /**
     * @dataProvider getThemeResolvers
     */
    public function testTrustedResolvers($themeResolver)
    {
        $this->investigator->add('Jungi\Bundle\ThemeBundle\Resolver\CookieThemeResolver');

        $this->assertFalse($this->investigator->isSuspect($themeResolver));
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
