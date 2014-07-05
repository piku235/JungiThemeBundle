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

use Jungi\Bundle\ThemeBundle\Core\ThemeHolderInterface;

/**
 * SimpleThemeHolder is a default theme holder
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class SimpleThemeHolder implements ThemeHolderInterface
{
    /**
     * @var ThemeInterface
     */
    protected $theme;

    /**
     * (non-PHPdoc)
     * @see \Jungi\Bundle\ThemeBundle\Core\ThemeHolderInterface::getTheme()
     */
    public function getTheme()
    {
        return $this->theme;
    }

    /**
     * (non-PHPdoc)
     * @see \Jungi\Bundle\ThemeBundle\Core\ThemeHolderInterface::setTheme()
     */
    public function setTheme(ThemeInterface $theme)
    {
        $this->theme = $theme;
    }
}