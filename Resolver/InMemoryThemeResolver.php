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
 * This class can be used as the default theme resolver
 *
 * As the class name says the theme name is stored in a memory space shared by variable
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
     * @param bool   $fixed Is a fixed? (optional)
     *
     * @throws \LogicException If the theme name is not passed when the $fixed is false
     */
    public function __construct($theme = null, $fixed = true)
    {
        if (!$fixed && null === $theme) {
            throw new \LogicException('You must provide the theme name if you are not using fixed state.');
        }

        $this->theme = $theme;
        $this->fixed = $fixed;
    }

    /**
     * {@inheritdoc}
     */
    public function resolveThemeName(Request $request)
    {
        return $this->theme;
    }

    /**
     * {@inheritdoc}
     *
     * @throws \BadMethodCallException When the fixed state is switched on
     */
    public function setThemeName($themeName, Request $request)
    {
        if ($this->fixed) {
            throw new \BadMethodCallException('This theme resolver does not allow for setting new themes.');
        }

        $this->theme = $themeName;
    }
}
