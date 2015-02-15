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

use Jungi\Bundle\ThemeBundle\Core\VirtualThemeInterface;
use Jungi\Bundle\ThemeBundle\Matcher\Filter\ThemeCollection;
use Jungi\Bundle\ThemeBundle\Matcher\Filter\ThemeFilterInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * The class is used to match a virtual theme name to an appropriate theme instance
 *
 * It supports only ThemeNameReference instances
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class VirtualThemeMatcher implements VirtualThemeMatcherInterface
{
    /**
     * @var ThemeFilterInterface[]
     */
    protected $filters;

    /**
     * Constructor
     *
     * @param ThemeFilterInterface[] $filters Theme filters
     */
    public function __construct(array $filters = array())
    {
        $this->filters = array();
        foreach ($filters as $filter) {
            $this->addFilter($filter);
        }
    }

    /**
     * Adds a theme filter
     *
     * @param ThemeFilterInterface $filter A theme filter
     *
     * @return void
     */
    public function addFilter(ThemeFilterInterface $filter)
    {
        $this->filters[] = $filter;
    }

    /**
     * {@inheritdoc}
     *
     * @throws \InvalidArgumentException If the given themes array is empty
     * @throws \RuntimeException         When there is no matching theme
     * @throws \RuntimeException         When there is more than one matching theme
     */
    public function match(VirtualThemeInterface $theme, Request $request)
    {
        $themes = $theme->getThemes();
        $count = count($themes);
        switch ($count) {
            case 0:
                throw new \InvalidArgumentException('The theme set cannot be empty.');
            case 1:
                return reset($themes);
        }

        $collection = new ThemeCollection($themes);
        foreach ($this->filters as $filter) {
            $filter->filter($collection, $request);
            if (1 === $count = count($collection)) {
                break;
            }
        }

        switch ($count) {
            case 1:
                // passed
                return $collection->first();
            case 0:
                // not passed
                throw new \RuntimeException('There is no matching theme for the given theme set.');
            default:
                // not passed
                throw new \RuntimeException('There is more than one matching theme for the given themes set.');
        }
    }
}
