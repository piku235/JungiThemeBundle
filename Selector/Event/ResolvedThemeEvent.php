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
     * Constructor
     *
     * @param ThemeInterface         $theme    A theme
     * @param ThemeResolverInterface $resolver A theme resolver
     * @param Request                $request  A Request object
     */
    public function __construct(ThemeInterface $theme, ThemeResolverInterface $resolver, Request $request)
    {
        $this->resolver = $resolver;

        parent::__construct($theme, $request);
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
