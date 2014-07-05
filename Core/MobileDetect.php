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
 * MobileDetect only extends the Mobile_Detect class
 * and fits it for the symfony
 *
 * @author      Current authors: Serban Ghita <serbanghita@gmail.com>, Nick Ilyin <nick.ilyin@gmail.com>
 *              Original author: Victor Stanciu <vic.stanciu@gmail.com>
 * @author      Modifier: Piotr Kugla <piku235@gmail.com>
 * @license     Code and contributions have 'MIT License'
 *              More details: https://github.com/serbanghita/Mobile-Detect/blob/master/LICENSE.txt
 */
class MobileDetect extends \Mobile_Detect
{
    /**
     * Constructor
     *
     * @param RequestStack $requestStack A request stack (optional)
     */
    public function __construct(RequestStack $requestStack = null)
    {
        if (null !== $requestStack && null !== $requestStack->getCurrentRequest()) {
            $this->handleRequest($requestStack->getCurrentRequest());
        }
    }

    /**
     * Method is useless for the symfony
     *
     * @deprecated
     */
    public function setHttpHeaders($httpHeaders = null)
    {
        throw new \BadMethodCallException('Rather use a handleRequest method with a Request instance.');
    }

    /**
     * Method is useless for the symfony
     *
     * @deprecated
     */
    public function setUserAgent($userAgent = null)
    {
        throw new \BadMethodCallException('Rather use a handleRequest method with a Request instance.');
    }

    /**
     * Handles a given request
     *
     * @param Request $request A request
     *
     * @return void
     */
    public function handleRequest(Request $request)
    {
        // Set all HTTP headers
        $this->httpHeaders = array();
        foreach ($request->headers->all() as $key => $val) {
            $this->httpHeaders['HTTP_' . strtr(strtoupper($key), '-', '_')] = $val[0];
        }

        // Automatically detect the UA from set HTTP headers
        parent::setUserAgent(null);
    }

    /**
     * Detects a operating system (OS)
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