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

use Jungi\Bundle\ThemeBundle\Core\VirtualThemeInterface;
use Jungi\Bundle\ThemeBundle\Templating\TemplateReference;
use Jungi\Bundle\ThemeBundle\Templating\VirtualTemplateReference;
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
     * Returns a full path for a given template file
     *
     * @param TemplateReferenceInterface $template    A template
     * @param string                     $currentPath Unused
     * @param Boolean                    $first       Unused
     *
     * @return string
     *
     * @throws \RuntimeException         When the theme from a TemplateReference instance does not exist
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

        if (!$template instanceof TemplateReference) {
            return parent::locate($template, $currentPath, $first);
        }

        try {
            $theme = $this->registry->getTheme($template->get('theme'));
            if ($template instanceof VirtualTemplateReference) {
                if (!$theme instanceof VirtualThemeInterface) {
                    throw new \RuntimeException(sprintf('Bad reference'));
                }

                $childTheme = $theme->getThemes()->get($template->get('pointed_theme'));
                $themePath = $childTheme->getPath();
            } elseif ($theme instanceof VirtualThemeInterface && null === $theme->getPointedTheme()) {
                throw new \RuntimeException(sprintf('Virtual theme is not pointing to any theme.'));
            } else {
                $themePath = $theme->getPath();
            }

            $path = $themePath.'/'.$template->getPath();

            return $this->cache[$key] = $this->locator->locate($path);
        } catch (ThemeNotFoundException $e) {
            throw new \RuntimeException('The theme locator could not finish his job, see the previous exception.', null, $e);
        } catch (\Exception $e) {
            // Theme file is not exist, instead use the TemplateReferenceInterface instance path
            return parent::locate($template->getOrigin(), $currentPath, $first);
        }
    }
}
