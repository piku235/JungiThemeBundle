<?php

/**
 * Mobile Detect Library
 * =====================
 *
 * Motto: "Every business should have a mobile detection script to detect mobile readers"
 *
 * Mobile_Detect is a lightweight PHP class for detecting mobile devices (including tablets).
 * It uses the User-Agent string combined with specific HTTP headers to detect the mobile environment.
 *
 * @author      Current authors: Serban Ghita <serbanghita@gmail.com>
 *                               Nick Ilyin <nick.ilyin@gmail.com>
 *
 *              Original author: Victor Stanciu <vic.stanciu@gmail.com>
 *
 * @license     Code and contributions have 'MIT License'
 *              More details: https://github.com/serbanghita/Mobile-Detect/blob/master/LICENSE.txt
 *
 * @link        Homepage:     http://mobiledetect.net
 *              GitHub Repo:  https://github.com/serbanghita/Mobile-Detect
 *              Google Code:  http://code.google.com/p/php-mobile-detect/
 *              README:       https://github.com/serbanghita/Mobile-Detect/blob/master/README.md
 *              HOWTO:        https://github.com/serbanghita/Mobile-Detect/wiki/Code-examples
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
     */
    public function setHttpHeaders($httpHeaders = null)
    {
        throw new \BadMethodCallException('Rather use a "handleRequest" method with a Request instance.');
    }

    /**
     * Useless for the symfony.
     *
     * @deprecated
     */
    public function setUserAgent($userAgent = null)
    {
        throw new \BadMethodCallException('Rather use a "handleRequest" method with a Request instance.');
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
