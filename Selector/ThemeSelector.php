<?php

/*
 * This file is part of the JungiThemeBundle package.
 *
 * (c) Piotr Kugla <piku235@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jungi\Bundle\ThemeBundle\Selector;

use Jungi\Bundle\ThemeBundle\Core\ThemeRegistryInterface;
use Jungi\Bundle\ThemeBundle\Exception\ThemeNotFoundException;
use Jungi\Bundle\ThemeBundle\Selector\Exception\NullThemeException;
use Jungi\Bundle\ThemeBundle\Resolver\ThemeResolverInterface;
use Jungi\Bundle\ThemeBundle\Selector\Event\DetailedResolvedThemeEvent;
use Jungi\Bundle\ThemeBundle\Event\HttpThemeEvent;
use Jungi\Bundle\ThemeBundle\Core\ThemeInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * ThemeSelector basically uses a theme resolver to get the appropriate theme for the request
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class ThemeSelector implements ThemeSelectorInterface
{
    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    /**
     * @var ThemeResolverInterface
     */
    private $fallback;

    /**
     * @var ThemeResolverInterface
     */
    private $resolver;

    /**
     * @var ThemeRegistryInterface
     */
    private $registry;

    /**
     * Constructor
     *
     * @param ThemeRegistryInterface   $registry   A theme registry
     * @param EventDispatcherInterface $dispatcher An event dispatcher
     * @param ThemeResolverInterface   $resolver   A theme resolver
     * @param ThemeResolverInterface   $fallback   A fallback theme resolver (optional)
     */
    public function __construct(ThemeRegistryInterface $registry, EventDispatcherInterface $dispatcher, ThemeResolverInterface $resolver, ThemeResolverInterface $fallback = null)
    {
        $this->dispatcher = $dispatcher;
        $this->registry = $registry;
        $this->resolver = $resolver;
        $this->fallback = $fallback;
    }

    /**
     * Selects an appropriate theme fora given Request
     *
     * If everything will go well a theme obtained from the primary theme resolver
     * will be returned otherwise a theme from the fallback theme resolver will be
     * returned
     *
     * @param Request $request A request instance
     *
     * @return ThemeInterface
     *
     * @throws \Exception If occurs
     */
    public function select(Request $request)
    {
        try {
            $theme = $this->getTheme($this->resolver->resolveThemeName($request), $request);

            // Dispatch the event
            $event = new DetailedResolvedThemeEvent(
                DetailedResolvedThemeEvent::PRIMARY_RESOLVER,
                $theme,
                $this->resolver,
                $request
            );
            $this->dispatcher->dispatch(ThemeSelectorEvents::RESOLVED, $event);
        } catch (\Exception $e) {
            // Use a fallback theme?
            if (null === $this->fallback) {
                throw $e;
            }

            $theme = $this->getTheme($this->fallback->resolveThemeName($request), $request);

            // Dispatch the event
            $event = new DetailedResolvedThemeEvent(
                DetailedResolvedThemeEvent::FALLBACK_RESOLVER,
                $theme,
                $this->fallback,
                $request
            );
            $this->dispatcher->dispatch(ThemeSelectorEvents::RESOLVED, $event);
        }

        // Dispatch the event
        $event = new HttpThemeEvent($theme, $request);
        $this->dispatcher->dispatch(ThemeSelectorEvents::SELECTED, $event);

        // If everything is ok, return the theme
        return $theme;
    }

    /**
     * Returns the theme instance for a given theme name
     *
     * @param string  $themeName A theme name
     * @param Request $request   A Request instance
     *
     * @return ThemeInterface
     *
     * @throws NullThemeException     When the theme name is null which means a theme resolver does
     *                                not have any theme
     */
    private function getTheme($themeName, Request $request)
    {
        if (null === $themeName) {
            throw new NullThemeException(sprintf('The theme for the request "%s" can not be found.', $request->getPathInfo()));
        }

        return $this->registry->getTheme($themeName);
    }
}
