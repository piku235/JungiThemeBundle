<?php

/*
 * This file is part of the JungiThemeBundle package.
 *
 * (c) Piotr Kugla <piku235@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jungi\Bundle\ThemeBundle\Matcher;

use Jungi\Bundle\ThemeBundle\Core\ThemeManagerInterface;
use Jungi\Bundle\ThemeBundle\Core\ThemeNameReference;
use Jungi\Bundle\ThemeBundle\Core\ThemeNameParserInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * The class is used to convert an unique theme name to an appropriate theme instance
 *
 * It supports only ThemeNameReference instances
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class StandardThemeMatcher implements ThemeMatcherInterface
{
    /**
     * @var ThemeNameParserInterface
     */
    protected $nameParser;

    /**
     * @var ThemeManagerInterface
     */
    protected $manager;

    /**
     * Constructor
     *
     * @param ThemeManagerInterface    $manager    A theme manager
     * @param ThemeNameParserInterface $nameParser A theme name parser
     */
    public function __construct(ThemeManagerInterface $manager, ThemeNameParserInterface $nameParser)
    {
        $this->manager = $manager;
        $this->nameParser = $nameParser;
    }

    /**
     * {@inheritdoc}
     */
    public function match($themeName, Request $request)
    {
        $themeName = $this->nameParser->parse($themeName);

        return $this->manager->getTheme($themeName->getName());
    }

    /**
     * {@inheritdoc}
     */
    public function supports($themeName)
    {
        $themeName = $this->nameParser->parse($themeName);

        return $themeName instanceof ThemeNameReference && !$themeName->isVirtual();
    }
}
