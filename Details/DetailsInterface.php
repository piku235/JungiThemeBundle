<?php

/*
 * This file is part of the JungiThemeBundle package.
 *
 * (c) Piotr Kugla <piku235@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jungi\Bundle\ThemeBundle\Details;

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
     * @return string|null
     */
    public function getVersion();

    /**
     * Returns the authors
     *
     * @return AuthorInterface[]
     */
    public function getAuthors();

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
}
