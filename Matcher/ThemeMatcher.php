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

use Jungi\Bundle\ThemeBundle\Core\ThemeManagerInterface;
use Jungi\Bundle\ThemeBundle\Core\ThemeNameReference;
use Jungi\Bundle\ThemeBundle\Core\ThemeNameReferenceInterface;
use Jungi\Bundle\ThemeBundle\Exception\ThemeNotFoundException;
use Jungi\Bundle\ThemeBundle\Matcher\Filter\ThemeCollection;
use Jungi\Bundle\ThemeBundle\Matcher\Filter\ThemeFilterInterface;
use Jungi\Bundle\ThemeBundle\Tag;
use Jungi\Bundle\ThemeBundle\Core\ThemeNameParserInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * ThemeMatcher
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class ThemeMatcher implements ThemeMatcherInterface
{
    /**
     * @var ThemeFilterInterface[]
     */
    private $filters;

    /**
     * @var ThemeNameParserInterface
     */
    private $nameParser;

    /**
     * @var ThemeManagerInterface
     */
    private $manager;

    /**
     * Constructor
     *
     * @param ThemeManagerInterface    $manager    A theme manager
     * @param ThemeNameParserInterface $nameParser A theme name parser
     * @param ThemeFilterInterface[]   $filters    A theme filter
     */
    public function __construct(ThemeManagerInterface $manager, ThemeNameParserInterface $nameParser, array $filters = array())
    {
        $this->manager = $manager;
        $this->nameParser = $nameParser;
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
     * @throws \InvalidArgumentException When the given theme name is invalid
     * @throws \RuntimeException When there is no matching theme
     * @throws \RuntimeException When there is more than one matching theme
     */
    public function match($themeName, Request $request)
    {
        if (!$themeName instanceof ThemeNameReferenceInterface) {
            $themeName = $this->nameParser->parse($themeName);
        }

        // is a virtual theme name?
        if ($themeName->isVirtual()) {
            $themes = $this->manager->findThemesWithTags(new Tag\Group($themeName->getName()));
            $count = count($themes);
            switch ($count) {
                case 0:
                    throw new ThemeNotFoundException($themeName);
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
                    throw new \RuntimeException(sprintf('There is no matching theme for the theme name "%s".', $themeName));
                default:
                    // not passed
                    throw new \RuntimeException(sprintf('There is more than one matching theme for the theme name "%s".', $themeName));
            }
        }

        return $this->manager->getTheme($themeName->getName());
    }
}
