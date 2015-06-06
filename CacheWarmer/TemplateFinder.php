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

use Jungi\Bundle\ThemeBundle\Core\ThemeInterface;
use Jungi\Bundle\ThemeBundle\Core\VirtualThemeInterface;
use Jungi\Bundle\ThemeBundle\Templating\TemplateFilenameParser;
use Jungi\Bundle\ThemeBundle\Core\ThemeSourceInterface;
use Jungi\Bundle\ThemeBundle\Templating\TemplateReference;
use Jungi\Bundle\ThemeBundle\Templating\VirtualTemplateReference;
use Symfony\Bundle\FrameworkBundle\CacheWarmer\TemplateFinderInterface;
use Symfony\Component\Finder\Finder;

/**
 * TemplateFinder looks for all template paths at each registered theme.
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class TemplateFinder implements TemplateFinderInterface
{
    /**
     * @var ThemeSourceInterface
     */
    private $registry;

    /**
     * @var TemplateFilenameParser
     */
    private $parser;

    /**
     * Constructor.
     *
     * @param ThemeSourceInterface   $themeReg A theme registry
     * @param TemplateFilenameParser $parser   A template name parser
     */
    public function __construct(ThemeSourceInterface $themeReg, TemplateFilenameParser $parser)
    {
        $this->registry = $themeReg;
        $this->parser = $parser;
    }

    /**
     * Looks for all templates in each theme.
     *
     * @return TemplateReference[]
     */
    public function findAllTemplates()
    {
        $result = array();
        foreach ($this->registry->getThemes() as $theme) {
            if ($theme instanceof VirtualThemeInterface) {
                $this->findInVirtualTheme($result, $theme);
            } else {
                $this->findInTheme($result, $theme);
            }
        }

        return $result;
    }

    private function findInVirtualTheme(array &$collection, VirtualThemeInterface $theme)
    {
        foreach ($theme->getThemes() as $childTheme) {
            $finder = new Finder();
            $finder
                ->files()
                ->followLinks()
                ->in($childTheme->getPath());

            foreach ($finder as $file) {
                $reference = $this->parser->parse($file->getRelativePathname());
                if (false !== $reference) {
                    $collection[] = new VirtualTemplateReference($reference, $theme->getName(), $childTheme->getName());
                }
            }
        }
    }

    private function findInTheme(array &$collection, ThemeInterface $theme)
    {
        $finder = new Finder();
        $finder
            ->files()
            ->followLinks()
            ->in($theme->getPath());

        foreach ($finder as $file) {
            $reference = $this->parser->parse($file->getRelativePathname());
            if (false !== $reference) {
                $collection[] = new TemplateReference($reference, $theme->getName());
            }
        }
    }
}
