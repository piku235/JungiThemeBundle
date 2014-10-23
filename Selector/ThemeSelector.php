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

use Jungi\Bundle\ThemeBundle\Exception\InvalidatedThemeException;
use Jungi\Bundle\ThemeBundle\Exception\NullThemeException;
use Jungi\Bundle\ThemeBundle\Core\ThemeManagerInterface;
use Jungi\Bundle\ThemeBundle\Resolver\ThemeResolverInterface;
use Jungi\Bundle\ThemeBundle\Selector\Event\DetailedResolvedThemeEvent;
use Jungi\Bundle\ThemeBundle\Exception\ThemeValidationException;
use Jungi\Bundle\ThemeBundle\Event\HttpThemeEvent;
use Jungi\Bundle\ThemeBundle\Core\ThemeInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * ThemeSelector basically uses a theme resolver to get the appropriate theme for the request
 *
 * But not only the theme resolver decides which theme will be used, a resolved theme can be easily changed
 * in the ResolvedThemeEvent. If there will be some problem with a resolved theme from the primary resolver
 * then the fallback resolver (if set) will be used to get the theme. Otherwise the ThemeSelector will end by throwing
 * an exception.
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
     * @var ThemeManagerInterface
     */
    protected $manager;

    /**
     * Constructor
     *
     * @param ThemeManagerInterface    $manager    A theme manager
     * @param EventDispatcherInterface $dispatcher An event dispatcher
     * @param ThemeResolverInterface   $resolver   A theme resolver
     * @param ThemeResolverInterface   $fallback   A fallback theme resolver (optional)
     */
    public function __construct(ThemeManagerInterface $manager, EventDispatcherInterface $dispatcher, ThemeResolverInterface $resolver, ThemeResolverInterface $fallback = null)
    {
        $this->manager = $manager;
        $this->dispatcher = $dispatcher;
        $this->resolver = $resolver;
        $this->fallback = $fallback;
    }

    /**
     * {@inheritdoc}
     */
    public function select(Request $request)
    {
        // Match the theme
        $theme = $this->matchTheme($request);

        // Dispatch the event
        $event = new HttpThemeEvent($theme, $request);
        $this->dispatcher->dispatch(ThemeSelectorEvents::SELECTED_THEME, $event);

        // If everything is ok, return the theme
        return $theme;
    }

    /**
     * Matches the theme for a given Request
     *
     * If everything will go well a theme obtained from the primary theme resolver
     * will be returned otherwise a theme from the fallback theme resolver will be
     * returned
     *
     * @param Request $request A Request
     *
     * @return ThemeInterface
     *
     * @throws \Exception If occurs
     */
    private function matchTheme(Request $request)
    {
        try {
            $theme = $this->getPrimaryTheme($request);
        } catch (\Exception $e) {
            // Use a fallback theme?
            if (null === $this->fallback) {
                throw $e;
            }

            $theme = $this->getFallbackTheme($request);
        }

        return $theme;
    }

    /**
     * Returns the standard matched theme for a given request
     *
     * Additionally the theme is validated if the validator was set
     *
     * @param Request $request A Request instance
     *
     * @return ThemeInterface
     *
     * @throws ThemeValidationException  When the validation will fail
     * @throws NullThemeException        When the theme resolver returns null
     * @throws InvalidatedThemeException If the NullResolvedThemeEvent will invalidate the theme
     */
    private function getPrimaryTheme(Request $request)
    {
        if (null === $themeName = $this->resolver->resolveThemeName($request)) {
            throw new NullThemeException(sprintf('The theme for the request "%s" can not be found.', $request->getPathInfo()));
        }

        // Theme
        $theme = $this->manager->getTheme($themeName);

        // Dispatch the event
        $event = new DetailedResolvedThemeEvent(DetailedResolvedThemeEvent::PRIMARY_RESOLVER, $theme, $this->resolver, $request);
        $this->dispatcher->dispatch(ThemeSelectorEvents::RESOLVED_THEME, $event);

        // Check if the theme is still in the event
        if (null === $theme = $event->getTheme()) {
            throw new InvalidatedThemeException(sprintf('The theme "%s" for the request "%s" has been invalidated.', $themeName, $request->getPathInfo()));
        }

        return $theme;
    }

    /**
     * Returns the fallback theme for a given request
     *
     * @param Request $request A Request instance
     *
     * @return ThemeInterface
     *
     * @throws \RuntimeException  If the fallback theme resolver was not set
     * @throws NullThemeException When the theme resolver will return null
     */
    private function getFallbackTheme(Request $request)
    {
        if (null === $this->fallback) {
            throw new \RuntimeException('The fallback theme resolver was not set.');
        } elseif (null === $themeName = $this->fallback->resolveThemeName($request)) {
            throw new NullThemeException(sprintf('The fallback theme for the request "%s" can not be found.', $request->getPathInfo()));
        }

        // Theme
        $theme = $this->manager->getTheme($themeName);

        // Dispatch the event
        $event = new DetailedResolvedThemeEvent(DetailedResolvedThemeEvent::FALLBACK_RESOLVER, $theme, $this->fallback, $request, false);
        $this->dispatcher->dispatch(ThemeSelectorEvents::RESOLVED_THEME, $event);

        return $event->getTheme();
    }
}
