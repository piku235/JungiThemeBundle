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

/**
 * The theme resolvers which implements this interface will be able to writes theme changes
 * from a Request to a Response
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
interface ResponseWriterInterface
{
    /**
     * Writes the theme changes from a given request to a given response
     *
     * @param Request $request A request instance
     * @param Response $response A response instance
     *
     * @return void
     */
    public function writeResponse(Request $request, Response $response);
}