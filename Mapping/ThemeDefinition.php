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
     * @var Tag[]
     */
    protected $tags = array();

    /**
     * @var ThemeInfo
     */
    protected $info;

    /**
     * @param array $tags
     *
     * @return ThemeDefinition
     */
    public function setTags(array $tags)
    {
        $this->tags = array();
        foreach ($tags as $tag) {
            $this->addTag($tag);
        }

        return $this;
    }

    /**
     * @param Tag $definition
     *
     * @return ThemeDefinition
     */
    public function addTag(Tag $definition)
    {
        $this->tags[] = $definition;

        return $this;
    }

    /**
     * @return Tag[]
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @param ThemeInfo $definition
     *
     * @return ThemeDefinition
     */
    public function setInformation(ThemeInfo $definition)
    {
        $this->info = $definition;

        return $this;
    }

    /**
     * @return ThemeInfo
     */
    public function getInformation()
    {
        return $this->info;
    }
}
