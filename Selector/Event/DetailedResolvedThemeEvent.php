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
use Jungi\Bundle\ThemeBundle\Core\ThemeNameReferenceInterface;
use Jungi\Bundle\ThemeBundle\Resolver\ThemeResolverInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * DetailedResolvedThemeEvent
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class DetailedResolvedThemeEvent extends ResolvedThemeEvent
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
     * @var string
     */
    protected $resolverType;

    /**
     * Constructor
     *
     * @param string                             $resolverType A theme resolver type
     * @param string|ThemeNameReferenceInterface $themeName    A theme name from which the theme was resolved
     * @param ThemeInterface                     $theme        A theme
     * @param ThemeResolverInterface             $resolver     A theme resolver
     * @param Request                            $request      A Request object
     * @param bool                               $clearTheme   Whether the theme in the event can be cleared (optional)
     *
     * @throws \InvalidArgumentException When the theme resolver type is invalid
     */
    public function __construct($resolverType, $themeName, ThemeInterface $theme, ThemeResolverInterface $resolver, Request $request, $clearTheme = true)
    {
        $types = array(self::PRIMARY_RESOLVER, self::FALLBACK_RESOLVER);
        if (!in_array($resolverType, $types)) {
            throw new \InvalidArgumentException(sprintf(
                'The given theme resolver type "%s" is invalid, the supported types: %s.',
                $resolverType,
                implode(', ', $types)
            ));
        }

        $this->resolverType = $resolverType;

        // Parent
        parent::__construct($themeName, $theme, $resolver, $request, $clearTheme);
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
}
