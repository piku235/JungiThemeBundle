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
use Jungi\Bundle\ThemeBundle\Selector\ThemeSelector;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Jungi\Bundle\ThemeBundle\Core\ThemeHolder;
use Jungi\Bundle\ThemeBundle\Core\ThemeSource;
use Jungi\Bundle\ThemeBundle\Tests\Fixtures\Resolver\FakeThemeResolver;

/**
 * ThemeChanger Test Case.
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
     * @var ThemeSource
     */
    private $registry;

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
        $this->registry = new ThemeSource(array(
            $this->createThemeMock('footheme'),
            $this->createThemeMock('bootheme'),
        ));
        $dispatcher = new EventDispatcher();
        $this->changer = new ThemeChanger(
            new ThemeSelector($this->registry, $dispatcher, $this->resolver),
            $this->resolver,
            $dispatcher
        );
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
        );
    }
}
