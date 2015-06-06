<?php

/*
 * This file is part of the JungiThemeBundle package.
 *
 * (c) Piotr Kugla <piku235@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jungi\Bundle\ThemeBundle\CacheWarmer;

use Symfony\Bundle\FrameworkBundle\CacheWarmer\TemplateFinderInterface;

/**
 * ChainTemplateFinder is a collection of TemplateFinderInterface instances
 * and its job is to get all template paths of each template finder.
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class ChainTemplateFinder implements TemplateFinderInterface
{
    /**
     * @var TemplateFinderInterface[]
     */
    protected $finders;

    /**
     * Constructor.
     *
     * @param TemplateFinderInterface[] $finders Template finders (optional)
     */
    public function __construct(array $finders = array())
    {
        $this->finders = array();
        foreach ($finders as $finder) {
            $this->addFinder($finder);
        }
    }

    /**
     * Adds a template finder.
     *
     * @param TemplateFinderInterface $finder A template finder
     */
    public function addFinder(TemplateFinderInterface $finder)
    {
        $this->finders[] = $finder;
    }

    /**
     * Find all the templates in each TemplateFinderInterface instance.
     *
     * @return array
     */
    public function findAllTemplates()
    {
        $result = array();
        foreach ($this->finders as $finder) {
            $result = array_merge($finder->findAllTemplates(), $result);
        }

        return $result;
    }
}
