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
use Jungi\Bundle\ThemeBundle\Resolver\SessionThemeResolver;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Symfony\Component\HttpFoundation\Session\Attribute\AttributeBag;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;

/**
 * SessionThemeResolverTest
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class SessionThemeResolverTest extends TestCase
{
    /**
     * @var SessionThemeResolver
     */
    private $resolver;

    /**
     * @var Request
     */
    private $request;

    /**
     * (non-PHPdoc)
     * @see PHPUnit_Framework_TestCase::setUp()
     */
    protected function setUp()
    {
        $this->resolver = new SessionThemeResolver();
        $this->request = $this->createDesktopRequest();
        $this->request->setSession(new Session(new MockArraySessionStorage(), new AttributeBag(), new FlashBag()));
    }

    /**
     * (non-PHPdoc)
     * @see PHPUnit_Framework_TestCase::tearDown()
     */
    protected function tearDown()
    {
        $this->resolver = null;
        $this->request = null;
    }

    /**
     * Tests when the request has set theme in the session
     */
    public function testOnStandardRequest()
    {
        // Sets a theme name
        $this->resolver->setThemeName('test', $this->request);

        // Assert on standard request
        $this->assertEquals('test', $this->resolver->resolveThemeName($this->request));
    }

    /**
     * Tests when a request does not have a session
     */
    public function testWhenThereIsNoSession()
    {
        // Without session
        $req = $this->createDesktopRequest();

        // Asserts
        $this->assertNull($this->resolver->resolveThemeName($req));
    }

    /**
     * Tests in the opposite from testOnRequestWithTheme
     */
    public function testOnMessyRequest()
    {
        $this->request->getSession()->set(SessionThemeResolver::SESSION_NAME . '_some_messy_things', 'test');

        $this->assertNull($this->resolver->resolveThemeName($this->request));
    }
}