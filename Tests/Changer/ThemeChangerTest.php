<?php

/*
 * This file is part of the JungiThemeBundle package.
 *
 * (c) Piotr Kugla <piku235@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jungi\Bundle\ThemeBundle\Tests;

use Jungi\Bundle\ThemeBundle\Changer\ThemeChanger;
use Jungi\Bundle\ThemeBundle\Core\ThemeNameParser;
use Jungi\Bundle\ThemeBundle\Core\ThemeNameReference;
use Jungi\Bundle\ThemeBundle\Matcher\ThemeMatcher;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Jungi\Bundle\ThemeBundle\Core\ThemeHolder;
use Jungi\Bundle\ThemeBundle\Core\ThemeManager;
use Jungi\Bundle\ThemeBundle\Tests\Fixtures\Resolver\FakeThemeResolver;

/**
 * ThemeChanger Test Case
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class ThemeChangerTest extends TestCase
{
    /**
     * @var ThemeChanger
     */
    private $changer;

    /**
     * @var ThemeHolder
     */
    private $holder;

    /**
     * @var ThemeManager
     */
    private $manager;

    /**
     * @var FakeThemeResolver
     */
    private $resolver;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->resolver = new FakeThemeResolver('bootheme', false);
        $this->manager = new ThemeManager(array(
            $this->createThemeMock('footheme'),
            $this->createThemeMock('bootheme')
        ));
        $nameParser = new ThemeNameParser();
        $matcher = new ThemeMatcher($this->manager, $nameParser);
        $this->changer = new ThemeChanger($matcher, $nameParser, $this->resolver, new EventDispatcher());
    }

    /**
     * @dataProvider getThemesForChange
     */
    public function testChange($theme, $matchTheme)
    {
        $request = $this->createDesktopRequest();
        $this->changer->change($theme, $request);

        $this->assertEquals($matchTheme, $this->resolver->resolveThemeName($request));
    }

    /**
     * @return array
     */
    public function getThemesForChange()
    {
        return array(
            array($this->createThemeMock('footheme'), 'footheme'),
            array('footheme', 'footheme'),
            array(new ThemeNameReference('bootheme'), 'bootheme')
        );
    }
}
