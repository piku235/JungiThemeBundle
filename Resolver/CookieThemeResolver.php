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
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Cookie;

/**
 * The CookieThemeResolver uses cookies for storing the theme name.
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class CookieThemeResolver implements ThemeResolverInterface, ResponseWriterInterface
{
    /**
     * @var string
     */
    const COOKIE_NAME = '_jungi_theme';

    /**
     * @var string
     */
    const ATTR_CHANGES = '_jungi_theme_changes';

    /**
     * @var array
     */
    private $options;

    /**
     * Constructor
     *
     * @param array $options Options for storing the cookie (optional)
     */
    public function __construct(array $options = array())
    {
        $this->options = $options + array(
            'expire' => time() + 2592000, // +30 days
            'path' => '/',
            'domain' => null,
            'secure' => false,
            'httpOnly' => true
        );

        if (isset($this->options['lifetime'])) {
            $this->options['expire'] = time() + $this->options['lifetime'];
        }
    }

    /**
     * (non-PHPdoc)
     * @see \Jungi\Bundle\ThemeBundle\Resolver\ThemeResolverInterface::resolve()
     */
    public function resolveThemeName(Request $request)
    {
        return $request->cookies->get(self::COOKIE_NAME);
    }

    /**
     * (non-PHPdoc)
     * @see \Jungi\Bundle\ThemeBundle\Resolver\ThemeResolverInterface::setTheme()
     */
    public function setThemeName($themeName, Request $request)
    {
        $request->cookies->set(self::COOKIE_NAME, $themeName);
        $request->attributes->set(self::ATTR_CHANGES, true);
    }

    /**
     * Writes theme changes done in a given request to a given response
     *
     * @param Request $request A request instance
     * @param Response $response A response instance
     *
     * @return void
     */
    public function writeResponse(Request $request, Response $response)
    {
        // Check if there were any changes in the request
        if (!$request->attributes->get(self::ATTR_CHANGES)) {
            return;
        }

        $response->headers->setCookie(new Cookie(
            self::COOKIE_NAME,
            $this->resolveThemeName($request),
            $this->options['expire'],
            $this->options['path'],
            $this->options['domain'],
            $this->options['secure'],
            $this->options['httpOnly']
        ));
    }
}
