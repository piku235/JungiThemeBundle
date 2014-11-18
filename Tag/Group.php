<?php

/*
 * This file is part of the JungiThemeBundle package.
 *
 * (c) Piotr Kugla <piku235@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jungi\Bundle\ThemeBundle\Tag;

/**
 * Group tag is used to connect multiple themes into one
 *
 * Generally the tag is used by AWD (Adaptive Web Design)
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class Group implements TagInterface
{
    /**
     * @var string
     */
    protected $group;

    /**
     * Constructor
     *
     * @param string $group A group name
     */
    public function __construct($group)
    {
        $this->group = $group;
    }

    /**
     * Returns the group name
     *
     * @return string
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * {@inheritdoc}
     */
    public function isEqual(TagInterface $tag)
    {
        return $tag == $this;
    }

    /**
     * {@inheritdoc}
     */
    public static function getName()
    {
        return 'jungi.group';
    }
}
