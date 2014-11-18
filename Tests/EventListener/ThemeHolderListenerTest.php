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

use Jungi\Bundle\ThemeBundle\Changer\Event\ChangeThemeEvent;
use Jungi\Bundle\ThemeBundle\Core\ThemeHolder;
use Jungi\Bundle\ThemeBundle\EventListener\ThemeHolderListener;
use Jungi\Bundle\ThemeBundle\Selector\Exception\NullThemeException;
use Jungi\Bundle\ThemeBundle\Tests\TestCase;

/**
 * ThemeHolderListener Test Case
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class ThemeHolderListenerTest extends TestCase
{
    /**
     * @var ThemeHolder
     */
    private $holder;

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

    /**
     * @var ThemeHolderListener
     */
    private $listener;

    protected function setUp()
    {
        $this->holder = new ThemeHolder();
        $this->theme = $theme = $this->createThemeMock('foo');
        $this->request = $this->createDesktopRequest();
        $this->event = $this->getMock('Symfony\Component\HttpKernel\Event\FilterControllerEvent', array(), array(), '', false);
        $this->event
            ->expects($this->any())
            ->method('isMasterRequest')
            ->will($this->returnValue(true));
        $this->event
            ->expects($this->any())
            ->method('getRequest')
            ->will($this->returnValue($this->request));
        $selector = $this->getMock('Jungi\Bundle\ThemeBundle\Selector\ThemeSelectorInterface');
        $selector
            ->expects($this->any())
            ->method('select')
            ->will($this->returnCallback(function ($request) use ($theme) {
                if ($request->attributes->get('empty_theme')) {
                    throw new NullThemeException('empty_theme');
                }

                return $theme;
            }));
        $this->listener = new ThemeHolderListener($this->holder, $selector, false);
    }

    public function testOnChange()
    {
        $theme = $this->createThemeMock('footheme');
        $event = new ChangeThemeEvent('footheme', $theme, $this->createDesktopRequest());
        $this->listener->onChange($event);

        $this->assertSame($theme, $this->holder->getTheme());
    }

    public function testWithTheme()
    {
        $this->listener->onKernelController($this->event);
        $this->assertSame($this->theme, $this->holder->getTheme());
    }

    public function testWithoutThemeOnIgnoreNullTheme()
    {
        $reflection = new \ReflectionObject($this->listener);
        $property = $reflection->getProperty('ignoreNullTheme');
        $property->setAccessible(true);
        $property->setValue($this->listener, true);
        $this->request->attributes->set('empty_theme', true);

        $this->listener->onKernelController($this->event);
        $this->assertNull($this->holder->getTheme());
    }

    /**
     * @expectedException \Jungi\Bundle\ThemeBundle\Selector\Exception\NullThemeException
     */
    public function testWithoutTheme()
    {
        $this->request->attributes->set('empty_theme', true);
        $this->listener->onKernelController($this->event);
    }
}
