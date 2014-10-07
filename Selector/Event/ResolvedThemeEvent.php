<?php

/*
 * This file is part of the JungiThemeBundle package.
 *
 * (c) Piotr Kugla <piku235@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jungi\Bundle\ThemeBundle\Selector\Event;

use Jungi\Bundle\ThemeBundle\Core\ThemeInterface;
use Jungi\Bundle\ThemeBundle\Event\HttpThemeEvent;
use Jungi\Bundle\ThemeBundle\Resolver\ThemeResolverInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * ResolvedThemeEvent
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class ResolvedThemeEvent extends HttpThemeEvent
{
    /**
     * @var ThemeResolverInterface
     */
    protected $resolver;

    /**
     * @var bool
     */
    protected $clearTheme;

    /**
     * Constructor
     *
     * @param ThemeInterface         $theme      A theme
     * @param ThemeResolverInterface $resolver   A theme resolver
     * @param Request                $request    A Request object
     * @param bool                   $clearTheme Whether the theme in the event can be cleared (optional)
     *
     * @throws \InvalidArgumentException When the theme resolver type is invalid
     */
    public function __construct(ThemeInterface $theme, ThemeResolverInterface $resolver, Request $request, $clearTheme = true)
    {
        $this->clearTheme = $clearTheme;
        $this->resolver = $resolver;

        parent::__construct($theme, $request);
    }

    /**
     * Sets a theme
     *
     * @param ThemeInterface $theme A theme
     *
     * @return void
     */
    public function setTheme(ThemeInterface $theme)
    {
        $this->theme = $theme;
    }

    /**
     * Checks whether the theme in the event can be cleared by the "clearTheme" method
     *
     * @return bool
     */
    public function canClearTheme()
    {
        return $this->clearTheme;
    }

    /**
     * Clears the current theme and stops the execution of rest listeners
     *
     * It can be useful when the theme did not passed some conditions
     *
     * @return void
     *
     * @throws \BadMethodCallException When clearing theme ability is locked
     */
    public function clearTheme()
    {
        if (!$this->clearTheme) {
            throw new \BadMethodCallException('The theme cannot be cleared due to the locked status.');
        }

        $this->theme = null;
        $this->stopPropagation();
    }

    /**
     * Returns the theme resolver
     *
     * @return ThemeResolverInterface
     */
    public function getThemeResolver()
    {
        return $this->resolver;
    }
}
