<?php

/*
 * This file is part of the JungiThemeBundle package.
 *
 * (c) Piotr Kugla <piku235@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jungi\Bundle\ThemeBundle\Mapping\Dumper;

use Jungi\Bundle\ThemeBundle\Mapping\StandardThemeDefinition;
use Jungi\Bundle\ThemeBundle\Mapping\Tag;
use Jungi\Bundle\ThemeBundle\Mapping\ThemeDefinition;
use Jungi\Bundle\ThemeBundle\Mapping\ThemeDefinitionRegistryInterface;
use Jungi\Bundle\ThemeBundle\Mapping\VirtualThemeDefinition;
use Jungi\Bundle\ThemeBundle\Tag\Registry\TagClassRegistryInterface;

/**
 * PhpDumper.
 *
 * To dump a theme definition registry using this theme dumper
 * you must at first process a theme definition registry using
 * a theme processor.
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class PhpDumper implements DumperInterface
{
    /**
     * @var TagClassRegistryInterface
     */
    private $tagClassRegistry;

    /**
     * Constructor.
     *
     * @param TagClassRegistryInterface $tagClassRegistry A tag class registry
     */
    public function __construct(TagClassRegistryInterface $tagClassRegistry)
    {
        $this->tagClassRegistry = $tagClassRegistry;
    }

    /**
     * {@inheritdoc}
     */
    public function dump(ThemeDefinitionRegistryInterface $registry)
    {
        $themes = '';
        foreach ($registry->getThemeDefinitions() as $themeName => $theme) {
            $themes .= sprintf('$collection->add(%s);', $this->dumpTheme($themeName, $theme)).PHP_EOL;
        }

        return <<< EOFILE
<?php

\$collection = new \Jungi\Bundle\ThemeBundle\Core\ThemeCollection();
$themes
return \$collection;
EOFILE;
    }

    /**
     * Dumps a theme definition
     *
     * @param string $name A theme name
     * @param ThemeDefinition $definition A theme definition
     *
     * @return string|null
     */
    private function dumpTheme($name, ThemeDefinition $definition)
    {
        if ($definition instanceof VirtualThemeDefinition) {
            return $this->dumpVirtualTheme($name, $definition);
        } elseif ($definition instanceof StandardThemeDefinition) {
            return $this->dumpStandardTheme($name, $definition);
        }
    }

    /**
     * Dumps a virtual theme definition
     *
     * @param string $name A theme name
     * @param VirtualThemeDefinition $definition A theme definition
     *
     * @return string
     */
    private function dumpVirtualTheme($name, VirtualThemeDefinition $definition)
    {
        // Basics
        $info = $this->prependTab($this->dumpThemeInfo($definition));
        $tags = $this->prependTab($this->dumpTags($definition));

        // Themes
        $arrBody = '';
        if ($definition->getThemes()) {
            $themes = array();
            foreach ($definition->getThemes() as $childName => $childDefinition) {
                $themes[] = $this->dumpTheme($childName, $childDefinition);
            }

            $arrBody = PHP_EOL.$this->prependTab(implode(', ', $themes)).PHP_EOL;
        }
        $themes = $this->prependTab(sprintf("array(%s)", $arrBody));

        // Outcome
        return <<< EOVTHEME
new \Jungi\Bundle\ThemeBundle\Core\VirtualTheme(
    '$name',
$themes,
$info,
$tags
)
EOVTHEME;
    }

    /**
     * Dumps a standard theme definition
     *
     * @param string $name A theme name
     * @param StandardThemeDefinition $definition A theme name
     *
     * @return string
     */
    private function dumpStandardTheme($name, StandardThemeDefinition $definition)
    {
        $info = $this->prependTab($this->dumpThemeInfo($definition));
        $tags = $this->prependTab($this->dumpTags($definition));

        return <<< EOSTHEME
new \Jungi\Bundle\ThemeBundle\Core\Theme(
    '$name',
    '{$definition->getPath()}',
$info,
$tags
)
EOSTHEME;
    }

    /**
     * Dumps tags of the given theme definition
     *
     * @param ThemeDefinition $definition A theme definition
     *
     * @return string
     */
    private function dumpTags(ThemeDefinition $definition)
    {
        $body = '';
        if ($definition->getTags()) {
            $tags = array();
            foreach ($definition->getTags() as $tag) {
                $tags[] = $this->dumpTag($tag);
            }

            $body = PHP_EOL.$this->prependTab(implode(','.PHP_EOL, $tags)).PHP_EOL;
            $body = sprintf('array(%s)', $body);
        }

        return sprintf('new \Jungi\Bundle\ThemeBundle\Tag\TagCollection(%s)', $body);
    }

    /**
     * Dumps a theme tag
     *
     * @param Tag $definition A theme tag
     *
     * @return string
     */
    private function dumpTag(Tag $definition)
    {
        $class = $this->tagClassRegistry->getTagClass($definition->getName());
        $args = $definition->getArguments();
        foreach ($args as &$arg) {
            $arg = $this->dumpValue($arg);
        }

        return sprintf('new %s(%s)', $class, implode(', ', $args));
    }

    /**
     * Dumps php values
     *
     * @param mixed $value A php value
     *
     * @return string
     */
    protected function dumpValue($value)
    {
        if (is_array($value)) {
            $result = '';
            if ($value) {
                $arrValues = array();
                foreach ($value as $childKey => $childValue) {
                    $arrValues[] = sprintf('%s => %s', $this->dumpValue($childKey), $this->dumpValue($childValue));
                }

                $result = PHP_EOL.$this->prependTab(implode(','.PHP_EOL, $arrValues)).PHP_EOL;
            }

            $result = sprintf('array(%s)', $result);
        } else {
            $result = var_export($value, true);
            $result = preg_replace('/ {2}/', '    ', $result);
        }

        return $result;
    }

    /**
     * Dumps a theme info of the given theme definition
     *
     * @param ThemeDefinition $definition A theme definition
     *
     * @return string
     */
    protected function dumpThemeInfo(ThemeDefinition $definition)
    {
        if (null === $information = $definition->getInfo()) {
            return 'null';
        }

        $methods = array();
        if ($information->hasProperty('name')) {
            $methods[] = sprintf('->setName(%s)', $this->dumpValue($information->getProperty('name')));
        }
        if ($information->hasProperty('description')) {
            $methods[] = sprintf('->setDescription(%s)', $this->dumpValue($information->getProperty('description')));
        }
        if ($information->hasProperty('authors')) {
            foreach ($information->getProperty('authors') as $author) {
                // Name and email are required and that's why they should exist
                $args = array(
                    $this->dumpValue($author['name']),
                    $this->dumpValue($author['email']),
                );
                if (isset($author['homepage'])) {
                    $args[] = $this->dumpValue($author['homepage']);
                }

                $methods[] = sprintf('->addAuthor(new \Jungi\Bundle\ThemeBundle\Information\Author(%s))', implode(', ', $args));
            }
        }

        $methods[] = '->getThemeInfo()';
        $methods = $this->prependTab(implode(PHP_EOL, $methods));

        return <<< EOINFO
\Jungi\Bundle\ThemeBundle\Information\ThemeInfoEssence::createBuilder()
$methods
EOINFO;
    }

    /**
     * Prepends tab spaces to the given content
     *
     * @param string $content A content
     * @param int $count A count of tabs (optional)
     * @param int $startLine A start line from which apply tabs (optional)
     * @param int|null $endLine An end line from which apply tabs (optional)
     *
     * @return string
     */
    protected function prependTab($content, $count = 1, $startLine = 0, $endLine = null)
    {
        $parts = explode(PHP_EOL, $content);
        if (null === $endLine) {
            $endLine = count($parts);
        } elseif ($endLine < 0) {
            $endLine = count($parts) + $endLine;
        }

        if ($endLine > count($parts)) {
            throw new \OutOfBoundsException(sprintf(
                'The end line "%d" is greater than available total lines "%d".',
                $endLine,
                count($parts)
            ));
        }

        for ($i = $startLine; $i < $endLine; ++$i) {
            $parts[$i] = str_repeat('    ', $count).$parts[$i];
        }

        return implode(PHP_EOL, $parts);
    }
}
