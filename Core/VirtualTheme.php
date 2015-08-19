<?php

/*
 * This file is part of the JungiThemeBundle package.
 *
 * (c) Piotr Kugla <piku235@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jungi\Bundle\ThemeBundle\Core;

use Jungi\Bundle\ThemeBundle\Exception\ThemeNotFoundException;
use Jungi\Bundle\ThemeBundle\Information\ThemeInfo;
use Jungi\Bundle\ThemeBundle\Information\ThemeInfoEssence;
use Jungi\Bundle\ThemeBundle\Tag\TagCollection;

/**
 * VirtualTheme is a representation of themes that are aggregated with this virtual theme.
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class VirtualTheme implements VirtualThemeInterface
{
    /**
     * @var string
     */
    protected $pointed;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var FrozenThemeCollection
     */
    protected $themes;

    /**
     * @var TagCollection
     */
    protected $tags;

    /**
     * @var ThemeInfo
     */
    protected $info;

    /**
     * Constructor.
     *
     * @param string           $name   An unique theme name
     * @param ThemeInterface[] $themes Themes that belongs to the virtual theme
     * @param ThemeInfo        $info   An information instance (optional)
     * @param TagCollection    $tags   Tags (optional)
     *
     * @throws \RuntimeException When in the children themes there is an another virtual theme
     */
    public function __construct($name, array $themes, ThemeInfo $info = null, TagCollection $tags = null)
    {
        $this->name = $name;
        $this->pointed = null;
        $this->info = $info ?: ThemeInfoEssence::createBuilder()->setName('empty')->getThemeInfo();
        $this->tags = $tags ?: new TagCollection();

        foreach ($themes as $theme) {
            if ($theme instanceof VirtualThemeInterface) {
                throw new \RuntimeException(sprintf('You cannot attach a virtual theme to an another virtual theme.'));
            }
        }

        $this->themes = new FrozenThemeCollection($themes);
    }

    /**
     * {@inheritdoc}
     */
    public function setPointedTheme($theme)
    {
        if (is_string($theme)) {
            $themeName = $theme;
            $belongs = $this->themes->has($theme);
        } elseif ($theme instanceof ThemeInterface) {
            $themeName = $theme->getName();
            $belongs = $this->themes->contains($theme);
        } else {
            throw new \InvalidArgumentException(
                'The given theme has a wrong type. Expected a theme name or an instance of the ThemeInterface.'
            );
        }

        if (!$belongs) {
            throw new ThemeNotFoundException(sprintf(
                'The theme "%s" not belongs to the virtual theme "%s".',
                $themeName,
                $this->name
            ));
        }

        $this->pointed = $themeName;
    }

    /**
     * {@inheritdoc}
     */
    public function getPointedTheme()
    {
        if ($this->pointed) {
            return $this->themes->get($this->pointed);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     *
     * @throws \RuntimeException When the pointed theme is not set
     */
    public function getPath()
    {
        if (!$this->pointed) {
            throw new \RuntimeException('The path cannot be returned, because the decorated theme is not set.');
        }

        return $this->getPointedTheme()->getPath();
    }

    /**
     * {@inheritdoc}
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * Returns the theme collection of the virtual theme.
     *
     * @return FrozenThemeCollection
     */
    public function getThemes()
    {
        return $this->themes;
    }

    /**
     * {@inheritdoc}
     */
    public function getInformation()
    {
        return $this->info;
    }
}
