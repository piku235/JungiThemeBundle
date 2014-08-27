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

/**
 * DetailsInterface implementations are used by ThemeInterface instances in order to share
 * some important information about a theme.
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
interface DetailsInterface
{
    /**
     * Returns the friendly theme name
     *
     * @return string
     */
    public function getName();

    /**
     * Returns the version
     *
     * @return string
     */
    public function getVersion();

    /**
     * Returns the author
     *
     * @return string|null
     */
    public function getAuthor();

    /**
     * Returns the author email address
     *
     * @return string|null
     */
    public function getAuthorEmail();

    /**
     * Returns the author website
     *
     * @return string|null
     */
    public function getAuthorSite();

    /**
     * Returns the description
     *
     * @return string|null
     */
    public function getDescription();

    /**
     * Returns the license type
     *
     * @return string|null
     */
    public function getLicense();

    /**
     * Returns the location of a thumbnail
     *
     * @return string|null
     */
    public function getThumbnail();
}
