<?php

/*
 * This file is part of the JungiThemeBundle package.
 *
 * (c) Piotr Kugla <piku235@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jungi\Bundle\ThemeBundle\Event;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\EventDispatcher\Event;
use Jungi\Bundle\ThemeBundle\Core\ThemeInterface;

/**
 * HttpThemeEvent is a basic http theme event.
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class HttpThemeEvent extends Event
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * @var ThemeInterface
     */
    protected $theme;

    /**
     * Constructor.
     *
     * @param ThemeInterface $theme   A theme
     * @param Request        $request A Request object
     */
    public function __construct(ThemeInterface $theme, Request $request)
    {
        $this->theme = $theme;
        $this->request = $request;
    }

    /**
     * Returns the request object.
     *
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * Returns the theme.
     *
     * @return ThemeInterface
     */
    public function getTheme()
    {
        return $this->theme;
    }
}
