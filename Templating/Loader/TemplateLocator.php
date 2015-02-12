<?php

/*
 * This file is part of the JungiThemeBundle package.
 *
 * (c) Piotr Kugla <piku235@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jungi\Bundle\ThemeBundle\Templating\Loader;

use Jungi\Bundle\ThemeBundle\Templating\TemplateReference;
use Symfony\Bundle\FrameworkBundle\Templating\Loader\TemplateLocator as BaseTemplateLocator;
use Symfony\Component\Templating\TemplateReferenceInterface;
use Jungi\Bundle\ThemeBundle\Core\ThemeRegistryInterface;
use Symfony\Component\Config\FileLocatorInterface;
use Jungi\Bundle\ThemeBundle\Exception\ThemeNotFoundException;

/**
 * TemplateLocator returns a full path to a theme resource if exists, otherwise it will use
 * the locate method of the TemplateLocator class for return
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class TemplateLocator extends BaseTemplateLocator
{
    /**
     * @var ThemeRegistryInterface
     */
    private $registry;

    /**
     * Constructor
     *
     * @param ThemeRegistryInterface $registry A theme registry
     * @param FileLocatorInterface   $locator  A FileLocatorInterface instance
     * @param string                 $cacheDir The cache path (optional)
     */
    public function __construct(ThemeRegistryInterface $registry, FileLocatorInterface $locator, $cacheDir = null)
    {
        $this->registry = $registry;

        parent::__construct($locator, $cacheDir);
    }

    /**
     * Returns a full path for a given theme or a template file
     *
     * If TemplateReference instance is given or a path to a given
     * theme file can not be found it uses parent locate method
     *
     * @param TemplateReferenceInterface $template    A template
     * @param string                     $currentPath Unused
     * @param Boolean                    $first       Unused
     *
     * @return string
     *
     * @throws \RuntimeException         When the theme from a TemplateReference instance is not exist
     *                                   in the theme manager
     * @throws \InvalidArgumentException If the given $template is not an instance of the TemplateReferenceInterface
     */
    public function locate($template, $currentPath = null, $first = true)
    {
        if (!$template instanceof TemplateReferenceInterface) {
            throw new \InvalidArgumentException("The template must be an instance of the TemplateReferenceInterface.");
        }

        $key = $this->getCacheKey($template);
        if (isset($this->cache[$key])) {
            return $this->cache[$key];
        }

        if ($template instanceof TemplateReference) {
            try {
                $theme = $this->registry->getTheme($template->get('theme'));
                $path = $theme->getPath().'/'.$template->getPath();

                return $this->cache[$key] = $this->locator->locate($path);
            } catch (ThemeNotFoundException $e) {
                throw new \RuntimeException('The theme locator could not finish his job, see the previous exception.', null, $e);
            } catch (\Exception $e) {
                // Theme file is not exist, instead use the TemplateReferenceInterface instance path
                return parent::locate($template->getOrigin(), $currentPath, $first);
            }
        }

        return parent::locate($template, $currentPath, $first);
    }
}
