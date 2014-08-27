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
use Jungi\Bundle\ThemeBundle\Core\ThemeHolderInterface;
use Jungi\Bundle\ThemeBundle\Selector\Event\ResolvedThemeEvent;
use Jungi\Bundle\ThemeBundle\Exception\ThemeValidationException;
use Jungi\Bundle\ThemeBundle\Event\ThemeEvent;
use Jungi\Bundle\ThemeBundle\Core\ThemeInterface;
use Jungi\Bundle\ThemeBundle\Selector\Event\SmartResolvedThemeEvent;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * StandardThemeSelector generally uses a theme resolver to obtain an appropriate theme for the request
 *
 * But not only the theme resolver decides which theme will be used, a resolved theme can be easily changed
 * in the ResolvedThemeEvent. If there will be problem with a theme resolved from the main theme resolver
 * then the fallback theme resolver (if set) will look for a theme. Otherwise the StandardThemeSelector will
 * end by throwing an exception.
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class StandardThemeSelector implements ThemeSelectorInterface
{
    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    /**
     * @var ThemeHolderInterface
     */
    private $holder;

    /**
     * @var array
     */
    protected $options;

    /**
     * @var ThemeManagerInterface
     */
    protected $manager;

    /**
     * @var ThemeResolverInterface
     */
    protected $fallback;

    /**
     * @var ThemeResolverInterface
     */
    protected $resolver;

    /**
     * Constructor
     *
     * @param ThemeManagerInterface    $manager    A theme manager
     * @param ThemeHolderInterface     $holder     A theme holder
     * @param EventDispatcherInterface $dispatcher An event dispatcher
     * @param ThemeResolverInterface   $resolver   A theme resolver
     * @param array                    $options    Options (optional)
     * @param ThemeResolverInterface   $fallback   A fallback theme resolver (optional)
     */
    public function __construct(ThemeManagerInterface $manager, ThemeHolderInterface $holder, EventDispatcherInterface $dispatcher, ThemeResolverInterface $resolver, array $options = array(), ThemeResolverInterface $fallback = null)
    {
        $this->manager = $manager;
        $this->dispatcher = $dispatcher;
        $this->resolver = $resolver;
        $this->holder = $holder;
        $this->fallback = $fallback;
        $this->options = $options + array(
            'ignore_null_themes' => false
        );
    }

    /**
     * Sets a fallback theme resolver
     *
     * @param ThemeResolverInterface $resolver A fallback theme resolver
     *
     * @return void
     */
    public function setFallback(ThemeResolverInterface $resolver)
    {
        $this->fallback = $resolver;
    }

    /**
     * Sets an option
     *
     * @param string $name  An option name
     * @param mixed  $value A value
     *
     * @return void
     */
    public function setOption($name, $value)
    {
        $this->options[$name] = $value;
    }

    /**
     * Returns the option value
     *
     * @param  string $name An option name
     *
     * @return mixed  Null if the option is not exist
     */
    public function getOption($name)
    {
        return array_key_exists($name, $this->options) ? $this->options[$name] : null;
    }

    /**
     * {@inheritdoc}
     *
     * @throws NullThemeException If the given theme name is blank and ignore_null_themes is false
     */
    public function select(Request $request)
    {
        try {
            $theme = $this->matchTheme($request);
        } catch (NullThemeException $e) {
            if ($this->options['ignore_null_themes']) {
                return;
            }

            throw $e;
        }

        // The event
        $event = new ThemeEvent($theme, $this->manager, $this->resolver, $request);

        // Dispatch the event
        $this->dispatcher->dispatch(ThemeSelectorEvents::PRE_SET, $event);

        // If everything is ok set a theme to the holder
        $this->holder->setTheme($theme);

        // Dispatch the event
        $this->dispatcher->dispatch(ThemeSelectorEvents::POST_SET, $event);
    }

    /**
     * Matches the theme for a given request
     *
     * If everything will go well the theme obtained from a leading theme resolver
     * will be returned otherwise theme from the fallback theme resolver will be returned
     *
     * @param Request $request A request
     *
     * @return ThemeInterface
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
     * Additionally the theme is validated if the validator was set
     *
     * @param Request $request A request instance
     *
     * @return ThemeInterface
     *
     * @throws ThemeValidationException  When the validation will fail
     * @throws NullThemeException        When a theme resolver returns null
     * @throws InvalidatedThemeException If the NullResolvedThemeEvent will invalidate a theme
     */
    protected function getPrimaryTheme(Request $request)
    {
        if (null === $themeName = $this->resolver->resolveThemeName($request)) {
            throw new NullThemeException(sprintf('The theme for the request "%s" can not be found.', $request->getPathInfo()));
        }

        // Theme
        $theme = $this->manager->getTheme($themeName);

        // Dispatch the event
        $event = new SmartResolvedThemeEvent($theme, $this->manager, $this->resolver, $request);
        $this->dispatcher->dispatch(ThemeSelectorEvents::RESOLVED_THEME, $event);

        // Check if a theme is still in the event
        if (null === $theme = $event->getTheme()) {
            throw new InvalidatedThemeException(sprintf('The theme "%s" for the request "%s" has been invalidated.', $themeName, $request->getPathInfo()));
        }

        return $theme;
    }

    /**
     * Returns the fallback theme for a given request
     *
     * @param Request $request A request instance
     *
     * @return ThemeInterface
     *
     * @throws \RuntimeException  If a fallback theme resolver was not set
     * @throws NullThemeException When a theme resolver returns null
     */
    protected function getFallbackTheme(Request $request)
    {
        if (null === $this->fallback) {
            throw new \RuntimeException('The fallback theme resolver was not set.');
        } elseif (null === $themeName = $this->fallback->resolveThemeName($request)) {
            throw new NullThemeException(sprintf('The fallback theme for the request "%s" can not be found.', $request->getPathInfo()));
        }

        // Theme
        $theme = $this->manager->getTheme($themeName);

        // Dispatch the event
        $event = new ResolvedThemeEvent($theme, $this->manager, $this->fallback, $request);
        $this->dispatcher->dispatch(ThemeSelectorEvents::RESOLVED_THEME, $event);

        return $event->getTheme();
    }
}
