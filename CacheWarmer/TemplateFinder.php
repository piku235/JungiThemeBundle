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

use Jungi\Bundle\ThemeBundle\Templating\TemplateFilenameParser;
use Jungi\Bundle\ThemeBundle\Core\ThemeRegistryInterface;
use Jungi\Bundle\ThemeBundle\Templating\TemplateReference;
use Symfony\Bundle\FrameworkBundle\CacheWarmer\TemplateFinderInterface;
use Symfony\Component\Finder\Finder;

/**
 * TemplateFinder looks for all template paths at each registered theme
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class TemplateFinder implements TemplateFinderInterface
{
    /**
     * @var ThemeRegistryInterface
     */
    private $registry;

    /**
     * @var TemplateFilenameParser
     */
    private $parser;

    /**
     * Constructor
     *
     * @param ThemeRegistryInterface $themeReg A theme registry
     * @param TemplateFilenameParser $parser   A template name parser
     */
    public function __construct(ThemeRegistryInterface $themeReg, TemplateFilenameParser $parser)
    {
        $this->registry = $themeReg;
        $this->parser = $parser;
    }

    /**
     * Looks for all templates in each theme
     *
     * @return TemplateReference[]
     */
    public function findAllTemplates()
    {
        $result = array();
        foreach ($this->registry->getThemes() as $theme) {
            $finder = new Finder();
            $finder
                ->files()
                ->followLinks()
                ->in($theme->getPath());

            foreach ($finder as $file) {
                $reference = $this->parser->parse($file->getRelativePathname());
                if (false !== $reference) {
                    $result[] = new TemplateReference($reference, $theme->getName());
                }
            }
        }

        return $result;
    }
}
