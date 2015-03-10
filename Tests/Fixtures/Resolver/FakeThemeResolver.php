<?php

/*
 * This file is part of the JungiThemeBundle package.
 *
 * (c) Piotr Kugla <piku235@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jungi\Bundle\ThemeBundle\Tests\Fixtures\Resolver;

use Jungi\Bundle\ThemeBundle\Resolver\InMemoryThemeResolver;
use Jungi\Bundle\ThemeBundle\Resolver\ResponseWriterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * FakeThemeResolver with implemented ResponseWriterInterface.
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class FakeThemeResolver extends InMemoryThemeResolver implements ResponseWriterInterface
{
    /**
     * Writes the theme changes from a given request to a given response.
     *
     * @param Request  $request  A request instance
     * @param Response $response A response instance
     */
    public function writeResponse(Request $request, Response $response)
    {
        $response->headers->set('_theme', $this->resolveThemeName($request));
    }
}
