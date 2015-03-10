<?php

/*
 * This file is part of the JungiThemeBundle package.
 *
 * (c) Piotr Kugla <piku235@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jungi\Bundle\ThemeBundle\Mapping;

/**
 * ThemeDefinition.
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
abstract class ThemeDefinition
{
    /**
     * @var TagDefinition[]
     */
    protected $tags = array();

    /**
     * {@inheritdoc}
     */
    public function setTags(array $tags)
    {
        $this->tags = array();
        foreach ($tags as $tag) {
            $this->addTag($tag);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function addTag(TagDefinition $definition)
    {
        $this->tags[] = $definition;
    }

    /**
     * {@inheritdoc}
     */
    public function getTags()
    {
        return $this->tags;
    }
}
