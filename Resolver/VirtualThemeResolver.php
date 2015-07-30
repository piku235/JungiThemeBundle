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

use Jungi\Bundle\ThemeBundle\Core\ThemeCollection;
use Jungi\Bundle\ThemeBundle\Core\VirtualThemeInterface;
use Jungi\Bundle\ThemeBundle\Resolver\Filter\ThemeFilterInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * This class uses theme filters for resolving an appropriate theme.
 *
 * Theme filters task is to reject from a collection non matching themes until there will
 * be only one theme.
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class VirtualThemeResolver implements VirtualThemeResolverInterface
{
    /**
     * @var ThemeFilterInterface[]
     */
    protected $filters;

    /**
     * Constructor.
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
     * Adds a theme filter.
     *
     * @param ThemeFilterInterface $filter A theme filter
     */
    public function addFilter(ThemeFilterInterface $filter)
    {
        $this->filters[] = $filter;
    }

    /**
     * {@inheritdoc}
     *
     * @throws \InvalidArgumentException If the given virtual theme has not got any themes
     * @throws \RuntimeException         When there is no matching theme
     * @throws \RuntimeException         When there is more than one matching theme
     */
    public function resolveTheme(VirtualThemeInterface $theme, Request $request)
    {
        $themes = $theme->getThemes();
        $count = count($themes);
        switch ($count) {
            case 0:
                throw new \InvalidArgumentException('A theme collection cannot be empty.');
            case 1:
                return $themes->first();
        }

        $collection = new ThemeCollection($themes->all());
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
