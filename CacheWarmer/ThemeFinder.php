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

use Symfony\Component\Finder\Finder;
use Jungi\Bundle\ThemeBundle\Core\ThemeManagerInterface;
use Symfony\Bundle\FrameworkBundle\CacheWarmer\TemplateFinderInterface;
use Jungi\Bundle\ThemeBundle\Core\ThemeReference;
use Jungi\Bundle\ThemeBundle\Core\ThemeFilenameParser;

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
     * @var ThemeFilenameParser
     */
    private $parser;

    /**
     * Constructor
     *
     * @param ThemeManagerInterface $manager A theme manager
     * @param ThemeFilenameParser   $parser  A template name parser
     */
    public function __construct(ThemeManagerInterface $manager, ThemeFilenameParser $parser)
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
            $f = new Finder();
            $f
                ->files()
                ->followLinks()
                ->depth('< 3')
                ->in($theme->getPath())
            ;
            foreach ($f as $file) {
                $reference = $this->parser->parse($file->getRelativePathname());
                if (false !== $reference) {
                    $result[] = new ThemeReference($reference, $theme->getName());
                }
            }
        }

        return $result;
    }
}
