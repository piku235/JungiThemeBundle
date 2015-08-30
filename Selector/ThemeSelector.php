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

use Jungi\Bundle\ThemeBundle\Core\ThemeSourceInterface;
use Jungi\Bundle\ThemeBundle\Selector\Exception\NullThemeException;
use Jungi\Bundle\ThemeBundle\Resolver\ThemeResolverInterface;
use Jungi\Bundle\ThemeBundle\Selector\Event\DetailedResolvedThemeEvent;
use Jungi\Bundle\ThemeBundle\Event\HttpThemeEvent;
use Jungi\Bundle\ThemeBundle\Core\ThemeInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * ThemeSelector is a standard implementation of the ThemeSelectorInterface.
 *
 * It uses two theme resolvers for determining the current theme:
 *  primary theme resolver (required),
 *  fallback theme resolver (optional)
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
    private $fallbackResolver;

    /**
     * @var ThemeResolverInterface
     */
    private $primaryResolver;

    /**
     * @var ThemeSourceInterface
     */
    private $source;

    /**
     * Constructor.
     *
     * @param ThemeSourceInterface     $source     A theme source
     * @param EventDispatcherInterface $dispatcher An event dispatcher
     * @param ThemeResolverInterface   $primary    A primary theme resolver
     * @param ThemeResolverInterface   $fallback   A fallback theme resolver (optional)
     */
    public function __construct(ThemeSourceInterface $source, EventDispatcherInterface $dispatcher, ThemeResolverInterface $primary, ThemeResolverInterface $fallback = null)
    {
        $this->dispatcher = $dispatcher;
        $this->source = $source;
        $this->primaryResolver = $primary;
        $this->fallbackResolver = $fallback;
    }

    /**
     * Selects the current theme for the given Request.
     *
     * If everything will go well a theme obtained from the primary theme resolver
     * will be returned otherwise a theme from the fallback theme resolver will be
     * returned.
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
            $theme = $this->getTheme($this->primaryResolver->resolveThemeName($request), $request);

            // Dispatch the event
            $event = new DetailedResolvedThemeEvent(
                DetailedResolvedThemeEvent::PRIMARY_RESOLVER,
                $theme,
                $this->primaryResolver,
                $request
            );
            $this->dispatcher->dispatch(ThemeSelectorEvents::RESOLVED, $event);
        } catch (\Exception $e) {
            // Something bad happened, use a fallback theme?
            if (null === $this->fallbackResolver) {
                throw $e;
            }

            $theme = $this->getTheme($this->fallbackResolver->resolveThemeName($request), $request);

            // Dispatch the event
            $event = new DetailedResolvedThemeEvent(
                DetailedResolvedThemeEvent::FALLBACK_RESOLVER,
                $theme,
                $this->fallbackResolver,
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
     * Returns the theme instance for the given theme name.
     *
     * @param string  $themeName A theme name
     * @param Request $request   A Request instance
     *
     * @return ThemeInterface
     *
     * @throws NullThemeException When the theme name is null which means a theme resolver does
     *                            not have any theme
     */
    private function getTheme($themeName, Request $request)
    {
        if (null === $themeName) {
            throw new NullThemeException(sprintf('The theme for the request "%s" can not be found.', $request->getPathInfo()));
        }

        return $this->source->getTheme($themeName);
    }
}
