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
use Jungi\Bundle\ThemeBundle\Core\ThemeManagerInterface;
use Jungi\Bundle\ThemeBundle\Templating\TemplateReference;
use Symfony\Bundle\FrameworkBundle\CacheWarmer\TemplateFinderInterface;
use Symfony\Component\Finder\Finder;

/**
 * ThemeFinder looks for all template paths in each theme
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class ThemeFinder implements TemplateFinderInterface
{
    /**
     * @var ThemeManagerInterface
     */
    private $manager;

    /**
     * @var TemplateFilenameParser
     */
    private $parser;

    /**
     * Constructor
     *
     * @param ThemeManagerInterface  $manager A theme manager
     * @param TemplateFilenameParser $parser  A template name parser
     */
    public function __construct(ThemeManagerInterface $manager, TemplateFilenameParser $parser)
    {
        $this->manager = $manager;
        $this->parser = $parser;
    }

    /**
     * Looks for all templates in each theme
     *
     * @return array
     */
    public function findAllTemplates()
    {
        $result = array();
        foreach ($this->manager->getThemes() as $theme) {
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
