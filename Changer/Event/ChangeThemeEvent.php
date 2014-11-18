<?php

/*
 * This file is part of the JungiThemeBundle package.
 *
 * (c) Piotr Kugla <piku235@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jungi\Bundle\ThemeBundle\Changer\Event;

use Jungi\Bundle\ThemeBundle\Core\ThemeInterface;
use Jungi\Bundle\ThemeBundle\Core\ThemeNameReferenceInterface;
use Jungi\Bundle\ThemeBundle\Event\HttpThemeEvent;
use Symfony\Component\HttpFoundation\Request;

/**
 * ChangeThemeEvent
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class ChangeThemeEvent extends HttpThemeEvent
{
    /**
     * @var string|ThemeNameReferenceInterface
     */
    protected $themeName;

    /**
     * Constructor
     *
     * @param string|ThemeNameReferenceInterface $themeName A theme name from which the theme was matched
     * @param ThemeInterface                     $theme     A theme
     * @param Request                            $request   A Request object
     */
    public function __construct($themeName, ThemeInterface $theme, Request $request)
    {
        $this->themeName = $themeName;

        parent::__construct($theme, $request);
    }

    /**
     * Returns the theme name from which the theme was matched
     *
     * @return string|ThemeNameReferenceInterface
     */
    public function getThemeName()
    {
        return $this->themeName;
    }
}
