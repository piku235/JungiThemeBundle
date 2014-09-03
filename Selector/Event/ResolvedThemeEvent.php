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
     * @var string
     */
    const PRIMARY_RESOLVER = 'primary';

    /**
     * @var string
     */
    const FALLBACK_RESOLVER = 'fallback';

    /**
     * @var ThemeResolverInterface
     */
    protected $resolver;

    /**
     * @var string
     */
    protected $resolverType;

    /**
     * @var bool
     */
    protected $clearTheme;

    /**
     * Constructor
     *
     * @param ThemeInterface         $theme        A theme
     * @param string                 $resolverType A theme resolver type
     * @param ThemeResolverInterface $resolver     A theme resolver
     * @param Request                $request      A Request object
     * @param bool                   $clearTheme   Whether the theme in the event can be cleared (optional)
     *
     * @throws \InvalidArgumentException When the theme resolver type is invalid
     */
    public function __construct(ThemeInterface $theme, $resolverType, ThemeResolverInterface $resolver, Request $request, $clearTheme = true)
    {
        $types = array(self::PRIMARY_RESOLVER, self::FALLBACK_RESOLVER);
        if (!in_array($resolverType, $types)) {
            throw new \InvalidArgumentException(sprintf(
                'The given theme resolver type "%s" is invalid, the supported types: %s.',
                $resolverType,
                implode(', ', $types)
            ));
        }

        $this->clearTheme = $clearTheme;
        $this->resolver = $resolver;
        $this->resolverType = $resolverType;

        parent::__construct($theme, $request);
    }

    /**
     * Checks whether the theme in the event was resolved by given theme resolver type
     *
     * @param string $type A theme resolver type
     *
     * @return bool
     */
    public function wasResolvedBy($type)
    {
        return $this->resolverType == $type;
    }

    /**
     * Returns the theme resolver type
     *
     * @return string
     */
    public function getThemeResolverType()
    {
        return $this->resolverType;
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
     * @throws \BadMethodCallException When the clearing theme ability is locked
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
