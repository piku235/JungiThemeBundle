<?php

/*
 * This file is part of the JungiThemeBundle package.
 *
 * (c) Piotr Kugla <piku235@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jungi\Bundle\ThemeBundle\Core;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * This class only extends the Mobile_Detect class and fits it for the symfony environment.
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
final class MobileDetect extends \Mobile_Detect
{
    /**
     * Constructor.
     *
     * @param RequestStack $requestStack A RequestStack (optional)
     */
    public function __construct(RequestStack $requestStack = null)
    {
        // Not used here the parent constructor on purpose
        if (null !== $requestStack && null !== $request = $requestStack->getCurrentRequest()) {
            $this->handleRequest($request);
        }
    }

    /**
     * Useless for the symfony.
     *
     * @deprecated
     * @see MobileDetect::handleRequest
     */
    public function setHttpHeaders($httpHeaders = null)
    {
        throw new \BadMethodCallException('Please use instead the "handleRequest" method.');
    }

    /**
     * Useless for the symfony.
     *
     * @deprecated
     * @see MobileDetect::handleRequest
     */
    public function setUserAgent($userAgent = null)
    {
        throw new \BadMethodCallException('Please use instead the "handleRequest" method.');
    }

    /**
     * Handles a given Request.
     *
     * @param Request $request A request
     */
    public function handleRequest(Request $request)
    {
        // Set all HTTP headers
        $this->httpHeaders = array();
        foreach ($request->headers->all() as $key => $val) {
            $this->httpHeaders['HTTP_'.strtr(strtoupper($key), '-', '_')] = $val[0];
        }

        // Automatically detect the UA from the set HTTP headers
        parent::setUserAgent(null);
    }

    /**
     * Detects an operating system (OS).
     *
     * @return string|false False if no match
     */
    public function detectOS()
    {
        foreach (self::$operatingSystems as $os => $pattern) {
            if ($this->match($pattern)) {
                return $os;
            }
        }

        return false;
    }
}
