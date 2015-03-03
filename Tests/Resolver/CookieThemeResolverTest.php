<?php

/*
 * This file is part of the JungiThemeBundle package.
 *
 * (c) Piotr Kugla <piku235@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jungi\Bundle\ThemeBundle\Tests\Resolver;

use Jungi\Bundle\ThemeBundle\Tests\TestCase;
use Jungi\Bundle\ThemeBundle\Resolver\CookieThemeResolver;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

/**
 * CookieThemeResolver Test Case
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class CookieThemeResolverTest extends TestCase
{
    /**
     * @var CookieThemeResolver
     */
    private $resolver;

    /**
     * @var array
     */
    private $options;

    /**
     * Set up
     */
    protected function setUp()
    {
        $this->options = array(
            'lifetime' => 86400, // +24h
            'path' => '/foo',
            'domain' => 'fooweb.com',
            'secure' => true,
            'httpOnly' => false,
        );
        $this->resolver = new CookieThemeResolver($this->options);
    }

    /**
     * Tests resolve theme name method
     */
    public function testResolveThemeName()
    {
        $desktopReq = $this->createDesktopRequest();
        $helpReq = $this->createMobileRequest();
        $this->resolver->setThemeName('footheme', $desktopReq);

        $this->assertEquals('footheme', $this->resolver->resolveThemeName($desktopReq));
        $this->assertNull($this->resolver->resolveThemeName($helpReq));
    }

    /**
     * Tests writes to the response when they were theme changes
     */
    public function testWriteResponseOnChanges()
    {
        $response = new Response();
        $request = $this->createDesktopRequest();
        $this->resolver->setThemeName('footheme_new', $request);
        $this->resolver->writeResponse($request, $response);

        $cookies = $response->headers->getCookies(ResponseHeaderBag::COOKIES_ARRAY);
        $this->assertTrue(isset($cookies['fooweb.com']['/foo'][CookieThemeResolver::COOKIE_NAME]));
    }

    /**
     * Tests writes to the response when they were not any theme changes
     */
    public function testWriteResponseOnNoChanges()
    {
        $response = new Response();
        $request = $this->createDesktopRequest();
        $request->cookies->set(CookieThemeResolver::COOKIE_NAME, 'footheme_from_previous_request');
        $this->resolver->writeResponse($request, $response);

        // If cookies are empty then is fine
        $this->assertEmpty($response->headers->getCookies());
    }
}
