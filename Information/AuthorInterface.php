<?php

/*
 * This file is part of the JungiThemeBundle package.
 *
 * (c) Piotr Kugla <piku235@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jungi\Bundle\ThemeBundle\Information;

/**
 * AuthorInterface.
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
interface AuthorInterface
{
    /**
     * Returns the author name.
     *
     * @return string
     */
    public function getName();

    /**
     * Returns the author email.
     *
     * @return string
     */
    public function getEmail();

    /**
     * Returns the author homepage.
     *
     * @return string|null
     */
    public function getHomepage();
}
