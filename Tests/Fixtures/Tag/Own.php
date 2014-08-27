<?php

/*
 * This file is part of the JungiThemeBundle package.
 *
 * (c) Piotr Kugla <piku235@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jungi\Bundle\ThemeBundle\Tests\Fixtures\Tag;

use Jungi\Bundle\ThemeBundle\Tag\TagInterface;

/**
 * Own
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class Own implements TagInterface
{
    /**
     * @var string
     */
    const SPECIAL = 'test';

    /**
     * @var string
     */
    private $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    public function isEqual(TagInterface $tag)
    {
        return $this == $tag;
    }

    public static function getName()
    {
        return 'jungi.own';
    }
}
