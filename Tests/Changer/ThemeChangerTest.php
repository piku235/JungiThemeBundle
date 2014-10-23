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
        $this->holder = new ThemeHolder();
        $this->manager = new ThemeManager(array(
            $this->createThemeMock('footheme'),
            $this->createThemeMock('bootheme')
        ));
        $this->changer = new ThemeChanger($this->manager, $this->holder, $this->resolver, new EventDispatcher());
    }

    /**
     * Tests change
     *
     * @dataProvider getThemesForChange
     */
    public function testChange($theme)
    {
        $request = $this->createDesktopRequest();
        $this->changer->change($theme, $request);

        $this->assertEquals('footheme', $this->resolver->resolveThemeName($request));
        $this->assertEquals('footheme', $this->holder->getTheme()->getName());
    }

    /**
     * Data provider
     *
     * @return array
     */
    public function getThemesForChange()
    {
        return array(
            array($this->createThemeMock('footheme')),
            array('footheme')
        );
    }
}
