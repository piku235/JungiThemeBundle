<?php

/*
 * This file is part of the JungiThemeBundle package.
 *
 * (c) Piotr Kugla <piku235@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jungi\Bundle\ThemeBundle\Tests\EventListener;

use Jungi\Bundle\ThemeBundle\Core\SimpleThemeHolder;
use Jungi\Bundle\ThemeBundle\EventListener\ThemeHolderListener;
use Jungi\Bundle\ThemeBundle\Exception\NullThemeException;
use Jungi\Bundle\ThemeBundle\Tests\TestCase;

/**
 * ThemeHolderListener Test Case
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class ThemeHolderListenerTest extends TestCase
{
    /**
     * @var SimpleThemeHolder
     */
    private $holder;

    /**
     * @var \Jungi\Bundle\ThemeBundle\Selector\ThemeSelector
     */
    private $selector;

    /**
     * @var \Symfony\Component\HttpKernel\Event\FilterControllerEvent
     */
    private $event;

    /**
     * @var \Jungi\Bundle\ThemeBundle\Core\ThemeInterface
     */
    private $theme;

    /**
     * @var \Symfony\Component\HttpFoundation\Request
     */
    private $request;

    protected function setUp()
    {
        $this->holder = new SimpleThemeHolder();
        $this->theme = $theme = $this->createThemeMock('foo');
        $this->selector = $this->getMock('Jungi\Bundle\ThemeBundle\Selector\ThemeSelectorInterface');
        $this->selector
            ->expects($this->once())
            ->method('select')
            ->will($this->returnCallback(function($request) use($theme) {
                if ($request->attributes->get('empty_theme')) {
                    throw new NullThemeException('empty_theme');
                }

                return $theme;
            }))
        ;
        $this->request = $this->createDesktopRequest();
        $this->event = $this->getMock('Symfony\Component\HttpKernel\Event\FilterControllerEvent', array(), array(), '', false);
        $this->event
            ->expects($this->once())
            ->method('isMasterRequest')
            ->will($this->returnValue(true))
        ;
        $this->event
            ->expects($this->once())
            ->method('getRequest')
            ->will($this->returnValue($this->request))
        ;
    }

    public function testWithTheme()
    {
        $listener = new ThemeHolderListener($this->holder, $this->selector, false);
        $listener->onKernelController($this->event);

        $this->assertEquals($this->theme, $this->holder->getTheme());
    }

    public function testWithoutThemeOnIgnoreNullTheme()
    {
        $this->request->attributes->set('empty_theme', true);
        $listener = new ThemeHolderListener($this->holder, $this->selector, true);
        $listener->onKernelController($this->event);

        $this->assertNull($this->holder->getTheme());
    }

    /**
     * @expectedException \Jungi\Bundle\ThemeBundle\Exception\NullThemeException
     */
    public function testWithoutTheme()
    {
        $this->request->attributes->set('empty_theme', true);
        $listener = new ThemeHolderListener($this->holder, $this->selector, false);
        $listener->onKernelController($this->event);
    }
} 