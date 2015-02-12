<?php

/*
 * This file is part of the JungiThemeBundle package.
 *
 * (c) Piotr Kugla <piku235@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jungi\Bundle\ThemeBundle\Changer;

use Jungi\Bundle\ThemeBundle\Event\HttpThemeEvent;
use Jungi\Bundle\ThemeBundle\Resolver\ThemeResolverInterface;
use Jungi\Bundle\ThemeBundle\Core\ThemeInterface;
use Jungi\Bundle\ThemeBundle\Selector\ThemeSelectorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * ThemeChanger is a simple implementation of the ThemeChangerInterface.
 *
 * It uses a ThemeResolverInterface instance for changing the current theme.
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class ThemeChanger implements ThemeChangerInterface
{
    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    /**
     * @var ThemeSelectorInterface
     */
    private $selector;

    /**
     * @var ThemeResolverInterface
     */
    private $resolver;

    /**
     * Constructor
     *
     * @param ThemeSelectorInterface   $selector   A theme selector
     * @param ThemeResolverInterface   $resolver   A theme resolver
     * @param EventDispatcherInterface $dispatcher An event dispatcher
     */
    public function __construct(ThemeSelectorInterface $selector, ThemeResolverInterface $resolver, EventDispatcherInterface $dispatcher)
    {
        $this->selector = $selector;
        $this->dispatcher = $dispatcher;
        $this->resolver = $resolver;
    }

    /**
     * {@inheritdoc}
     */
    public function change($theme, Request $request)
    {
        if (is_string($theme)) {
            $themeName = $theme;
        } elseif ($theme instanceof ThemeInterface) {
            $themeName = $theme->getName();
        } else {
            throw new \InvalidArgumentException(sprintf('The theme must be a theme name or a theme instance of the "ThemeInterface".'));
        }

        // Apply
        $this->resolver->setThemeName($themeName, $request);

        // Theme
        if (is_string($theme)) {
            $theme = $this->selector->select($request);
        }

        // Dispatch the event
        $this->dispatcher->dispatch(ThemeChangerEvents::CHANGED, new HttpThemeEvent($theme, $request));
    }
}
