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

use Jungi\Bundle\ThemeBundle\Core\Information\ThemeInfo;
use Jungi\Bundle\ThemeBundle\Core\Information\ThemeInfoEssence;
use Jungi\Bundle\ThemeBundle\Tag\TagCollection;

/**
 * Theme is a simple implementation of the ThemeInterface.
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class Theme implements ThemeInterface
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $path;

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
     * @param string        $name An unique theme name
     * @param string        $path A path to theme resources
     * @param ThemeInfo     $info An information instance (optional)
     * @param TagCollection $tags A tag collection (optional)
     */
    public function __construct($name, $path, ThemeInfo $info = null, TagCollection $tags = null)
    {
        $this->name = $name;
        $this->path = $path;
        $this->info = $info ?: ThemeInfoEssence::createBuilder()->setName('empty')->getThemeInfo();
        $this->tags = $tags ?: new TagCollection();
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
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * {@inheritdoc}
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * {@inheritdoc}
     */
    public function getInformation()
    {
        return $this->info;
    }
}
