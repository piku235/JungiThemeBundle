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
 * Theme resolvers which implements this interface will be able to write theme changes done
 * in the request to the response.
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
interface ResponseWriterInterface
{
    /**
     * Writes theme changes done in the given Request to the given Response.
     *
     * @param Request  $request  A Request instance
     * @param Response $response A Response instance
     */
    public function writeResponse(Request $request, Response $response);
}
