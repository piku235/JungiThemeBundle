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
 * StandardThemeDefinition.
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class StandardThemeDefinition extends ThemeDefinition
{
    /**
     * @var string
     */
    private $path;

    /**
     * Constructor.
     *
     * @param string    $path A path (optional)
     * @param Tag[]     $tags Tag definitions (optional)
     * @param ThemeInfo $info A theme info (optional)
     */
    public function __construct($path = null, array $tags = array(), ThemeInfo $info = null)
    {
        $this->path = $path;
        $this->info = $info;
        foreach ($tags as $tag) {
            $this->addTag($tag);
        }
    }

    /**
     * @param $path
     *
     * @return StandardThemeDefinition
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getPath()
    {
        return $this->path;
    }
}
