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
    const ATTR_MODIFIED = '_jungi_theme_modified';

    /**
     * @var array
     */
    private $options;

    /**
     * Constructor
     *
     * @param array $options Options about storing the cookie (optional)
     */
    public function __construct(array $options = array())
    {
        $this->options = $options + array(
            'lifetime' => 2592000, // +30 days
            'path' => '/',
            'domain' => null,
            'secure' => false,
            'httpOnly' => true,
        );
    }

    /**
     * {@inheritdoc}
     */
    public function resolveThemeName(Request $request)
    {
        return $request->cookies->get(self::COOKIE_NAME);
    }

    /**
     * {@inheritdoc}
     */
    public function setThemeName($themeName, Request $request)
    {
        $request->cookies->set(self::COOKIE_NAME, $themeName);
        $request->attributes->set(self::ATTR_MODIFIED, true);
    }

    /**
     * {@inheritdoc}
     */
    public function writeResponse(Request $request, Response $response)
    {
        // Check if there were any changes in the request
        if (!$request->attributes->get(self::ATTR_MODIFIED)) {
            return;
        }

        $response->headers->setCookie(new Cookie(
            self::COOKIE_NAME,
            $this->resolveThemeName($request),
            time() + $this->options['lifetime'],
            $this->options['path'],
            $this->options['domain'],
            $this->options['secure'],
            $this->options['httpOnly']
        ));
    }
}
