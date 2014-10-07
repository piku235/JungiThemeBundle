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

use Jungi\Bundle\ThemeBundle\Details\DetailsInterface;
use Jungi\Bundle\ThemeBundle\Tag\TagCollection;

/**
 * Theme is a simple implementation of the ThemeInterface
 *
 * All properties can be only set by the constructor
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
     * @var DetailsInterface
     */
    protected $details;

    /**
     * @var TagCollection
     */
    protected $tags;

    /**
     * Constructor
     *
     * @param string           $name    An unique theme name
     * @param string           $path    A path to theme resources
     * @param DetailsInterface $details A details
     * @param TagCollection    $tags    A tag collection (optional)
     */
    public function __construct($name, $path, DetailsInterface $details, TagCollection $tags = null)
    {
        $this->name = $name;
        $this->path = $path;
        $this->details = $details;
        $this->tags = $tags ?: new TagCollection();
    }

    /**
     * {@inheritdoc}
     */
    public function getDetails()
    {
        return $this->details;
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
}
