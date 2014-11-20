<?php

/*
 * This file is part of the JungiThemeBundle package.
 *
 * (c) Piotr Kugla <piku235@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jungi\Bundle\ThemeBundle\Matcher;

use Jungi\Bundle\ThemeBundle\Exception\UnsupportedException;
use Symfony\Component\HttpFoundation\Request;

/**
 * ChainThemeMatcher
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class ChainThemeMatcher implements ThemeMatcherInterface
{
    /**
     * @var ThemeMatcherInterface[]
     */
    protected $matchers;

    /**
     * Constructor
     *
     * @param ThemeMatcherInterface[] $matchers Theme matchers
     */
    public function __construct(array $matchers)
    {
        $this->matchers = array();
        foreach ($matchers as $matcher) {
            $this->addMatcher($matcher);
        }
    }

    /**
     * Adds a theme matcher to the chain
     *
     * @param ThemeMatcherInterface $matcher A theme matcher
     *
     * @return void
     */
    public function addMatcher(ThemeMatcherInterface $matcher)
    {
        $this->matchers[] = $matcher;
    }

    /**
     * {@inheritdoc}
     *
     * @throws UnsupportedException If none of theme matchers supports the given theme name
     */
    public function match($themeName, Request $request)
    {
        foreach ($this->matchers as $matcher) {
            if ($matcher->supports($themeName)) {
                return $matcher->match($themeName, $request);
            }
        }

        throw new UnsupportedException(sprintf(
            'None of theme matchers were able to match a theme instance for the "%s".',
            $themeName
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function supports($themeName)
    {
        foreach ($this->matchers as $matcher) {
            if ($matcher->supports($themeName)) {
                return true;
            }
        }

        return false;
    }
}