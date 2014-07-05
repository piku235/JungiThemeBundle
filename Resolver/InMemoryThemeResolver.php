<?php

/*
 * This file is part of the JungiThemeBundle package.
 *
 * (c) Piotr Kugla <piku235@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jungi\Bundle\ThemeBundle\Resolver;

use Symfony\Component\HttpFoundation\Request;

/**
 * The class is good for tests purposes and for simple uses
 *
 * As the class name says a theme name is allocated in memory space shared by variable
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class InMemoryThemeResolver implements ThemeResolverInterface
{
    /**
     * @var string
     */
    private $theme;

    /**
     * @var boolean
     */
    private $fixed;

    /**
     * Constructor
     *
     * @param string $theme A theme name (optional if $fixed var is true)
     * @param bool $fixed Is a fixed? (optional)
     *
     * @throws \LogicException
     */
    public function __construct($theme = null, $fixed = true)
    {
        if (!$fixed && null === $theme) {
            throw new \LogicException('You must provide a theme name if you are not using fixed state.');
        }

        $this->theme = $theme;
        $this->fixed = $fixed;
    }

    /**
     * (non-PHPdoc)
     * @see \Jungi\Bundle\ThemeBundle\Resolver\ThemeResolverInterface::resolve()
     */
    public function resolveThemeName(Request $request)
    {
        return $this->theme;
    }

    /**
     * (non-PHPdoc)
     * @see \Jungi\Bundle\ThemeBundle\Resolver\ThemeResolverInterface::setThemeName()
     */
    public function setThemeName($themeName, Request $request)
    {
        if ($this->fixed) {
            throw new \BadMethodCallException('This theme resolver does not allow for setting new themes.');
        }

        $this->theme = $themeName;
    }
}