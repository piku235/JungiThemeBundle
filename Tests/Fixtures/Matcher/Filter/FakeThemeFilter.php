<?php

namespace Jungi\Bundle\ThemeBundle\Tests\Fixtures\Matcher\Filter;

use Jungi\Bundle\ThemeBundle\Matcher\Filter\ThemeCollection;
use Jungi\Bundle\ThemeBundle\Matcher\Filter\ThemeFilterInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * FakeThemeFilter
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class FakeThemeFilter implements ThemeFilterInterface
{
    /**
     * @var bool
     */
    public $removeAll = false;

    /**
     * @var array
     */
    private $remove = array();

    /**
     * Adds a theme name which will be removed during filtering
     *
     * @param string $name A theme name
     *
     * @return void
     */
    public function remove($name)
    {
        $this->remove[] = $name;
    }

    /**
     * {@inheritdoc}
     */
    public function filter(ThemeCollection $themes, Request $request)
    {
        foreach ($themes as $theme) {
            if ($this->removeAll || in_array($theme->getName(), $this->remove)) {
                $themes->remove($theme);
            }
        }
    }
}