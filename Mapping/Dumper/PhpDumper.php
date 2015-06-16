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
            $themes .= sprintf('$collection->add(%s);', $this->dumpTheme($themeName, $theme))."\n";
        }

        return <<< EOFILE
<?php

use Jungi\Bundle\ThemeBundle\Core\ThemeCollection;
use Jungi\Bundle\ThemeBundle\Core\Theme;
use Jungi\Bundle\ThemeBundle\Core\VirtualTheme;
use Jungi\Bundle\ThemeBundle\Tag\TagCollection;
use Jungi\Bundle\ThemeBundle\Information\ThemeInfoEssence;
use Jungi\Bundle\ThemeBundle\Information\Author;

\$collection = new ThemeCollection();
$themes

return \$collection;
EOFILE;
    }

    private function dumpTheme($name, ThemeDefinition $definition)
    {
        if ($definition instanceof VirtualThemeDefinition) {
            return $this->dumpVirtualTheme($name, $definition);
        } elseif ($definition instanceof StandardThemeDefinition) {
            return $this->dumpStandardTheme($name, $definition);
        }

        return;
    }

    private function dumpVirtualTheme($name, VirtualThemeDefinition $definition)
    {
        $themes = array();
        foreach ($definition->getThemes() as $childName => $childDefinition) {
            $themes[] = $this->dumpTheme($childName, $childDefinition);
        }

        $themes = $this->prependTab(sprintf("array(\n%s\n)", $this->prependTab(implode(', ', $themes), 1)), 1);

        return <<< EOVTHEME
new VirtualTheme(
    '$name',
$themes,
{$this->dumpThemeInfo($definition)},
{$this->dumpTags($definition)}
)
EOVTHEME;
    }

    private function dumpStandardTheme($name, StandardThemeDefinition $definition)
    {
        return <<< EOSTHEME
new Theme(
    '$name',
    '{$definition->getPath()}',
{$this->dumpThemeInfo($definition)},
{$this->dumpTags($definition)}
)
EOSTHEME;
    }

    private function dumpThemeInfo(ThemeDefinition $definition)
    {
        if (null === $information = $definition->getInformation()) {
            return $this->prependTab('null', 1);
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
                $args = array(
                    $this->dumpValue($author['name']),
                    $this->dumpValue($author['email']),
                );
                if (isset($author['homepage'])) {
                    $args[] = $this->dumpValue($author['homepage']);
                }

                $methods[] = sprintf('->addAuthor(new Author(%s))', implode(', ', $args));
            }
        }

        $methods[] = '->getThemeInfo()';
        $methods = $this->prependTab(implode("\n", $methods), 2);

        return <<< EOINFO
    ThemeInfoEssence::createBuilder()
$methods
EOINFO;
    }

    private function dumpTags(ThemeDefinition $definition)
    {
        $tags = array();
        foreach ($definition->getTags() as $tag) {
            $tags[] = $this->dumpTag($tag).',';
        }

        $tags = implode("\n", $tags);

        return <<< EOTAGS
    new TagCollection(array(
$tags
    ))
EOTAGS;
    }

    private function dumpTag(Tag $definition)
    {
        $class = $this->tagClassRegistry->getTagClass($definition->getName());
        $args = $definition->getArguments();
        foreach ($args as &$arg) {
            $arg = $this->dumpValue($arg);
        }

        return $this->prependTab(sprintf('new %s(%s)', $class, implode(', ', $args)), 2);
    }

    private function dumpValue($value)
    {
        $result = var_export($value, true);
        $result = preg_replace('/ {2}/', '    ', $result);

        return $result;
    }

    private function prependTab($content, $num, $startLine = 0, $endLine = null)
    {
        $parts = explode("\n", $content);
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

        for ($i = $startLine; $i < $endLine; $i++) {
            $parts[$i] = str_repeat('    ', $num).$parts[$i];
        }

        return implode("\n", $parts);
    }
}
