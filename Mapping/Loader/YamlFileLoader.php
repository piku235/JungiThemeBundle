<?php

/*
 * This file is part of the JungiThemeBundle package.
 *
 * (c) Piotr Kugla <piku235@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jungi\Bundle\ThemeBundle\Mapping\Loader;

use Jungi\Bundle\ThemeBundle\Details\Author;
use Jungi\Bundle\ThemeBundle\Core\ThemeManagerInterface;
use Jungi\Bundle\ThemeBundle\Tag\Factory\TagFactoryInterface;
use Jungi\Bundle\ThemeBundle\Core\Theme;
use Jungi\Bundle\ThemeBundle\Tag\TagCollection;
use Jungi\Bundle\ThemeBundle\Details\Details;
use Jungi\Bundle\ThemeBundle\Tag\TagInterface;
use Symfony\Component\Config\FileLocatorInterface;
use Symfony\Component\Yaml\Yaml;

/**
 * YamlFileLoader is responsible for creating theme instances from a yaml mapping file
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class YamlFileLoader extends FileLoader
{
    /**
     * @var array
     */
    private $parameters;

    /**
     * @var LoaderHelper
     */
    private $helper;

    /**
     * Constructor
     *
     * @param ThemeManagerInterface $themeManager A theme manager
     * @param FileLocatorInterface  $locator      A file locator
     * @param TagFactoryInterface   $factory      A tag factory
     * @param LoaderHelper          $helper       A loader helper
     */
    public function __construct(ThemeManagerInterface $themeManager, FileLocatorInterface $locator, TagFactoryInterface $factory, LoaderHelper $helper)
    {
        parent::__construct($themeManager, $locator, $factory);

        $this->helper = $helper;
        $this->parameters = array();
    }

    /**
     * {@inheritdoc}
     */
    public function supports($file)
    {
        return pathinfo($file, PATHINFO_EXTENSION) == 'yml';
    }

    /**
     * Loads a yml theme mapping file
     *
     * @param string $file A file
     *
     * @return void
     *
     * @throws \RuntimeException If a file is not local
     * @throws \DomainException  If a file is not supported
     */
    public function load($file)
    {
        $path = $this->locator->locate($file);

        if (!stream_is_local($path)) {
            throw new \RuntimeException(sprintf('The "%s" file is not local.', $path));
        } elseif (!$this->supports($path)) {
            throw new \DomainException(sprintf('The given file "%s" is not supported.', $path));
        }

        $content = Yaml::parse($path, true);
        if (null === $content) { // If is an empty file

            return;
        }

        // Validate a mapping file
        $this->validate($content, $file);

        // Parameters
        $this->parseParameters($content);

        // Themes
        $this->parseThemes($content);

        // Clear tmp
        $this->parameters = array();
    }

    /**
     * Processes parameters
     *
     * @param array $content A file content
     *
     * @return void
     */
    private function parseParameters(array $content)
    {
        if (isset($content['parameters'])) {
            $this->parameters = $content['parameters'];
            array_walk_recursive($this->parameters, array($this, 'replaceByPhpValue'));
        }
    }

    /**
     * Parses themes
     *
     * @param array $content A configuration file content
     *
     * @return void
     */
    private function parseThemes(array $content)
    {
        foreach ($content['themes'] as $themeName => $specification) {
            $this->themeManager->addTheme($this->parseTheme($themeName, $specification));
        }
    }

    /**
     * Parses a theme
     *
     * @param string $themeName     A theme name
     * @param array  $specification A theme specification
     *
     * @return Theme
     */
    private function parseTheme($themeName, array $specification)
    {
        // Validation
        if (!isset($specification['path']) || !isset($specification['details'])) {
            throw new \InvalidArgumentException('The one or all of required parameters "path, details" are missing in the theme specification.');
        }
        if ($keys = array_diff(array_keys($specification), array('tags', 'path', 'details'))) {
            throw new \InvalidArgumentException(sprintf('The parameters "%s" are illegal in the theme specification.', implode(', ', $keys)));
        }

        return new Theme(
            $themeName,
            $this->locator->locate($specification['path']),
            $this->parseDetails($specification),
            $this->parseTags($specification)
        );
    }

    /**
     * Parses details specification
     *
     * @param array $specification A specification
     *
     * @return Details
     *
     * @throws \RuntimeException When something goes wrong while parsing details node
     */
    private function parseDetails(array $specification)
    {
        $details = &$specification['details'];

        // Parameters
        $this->replaceParameters($details);

        $builder = Details::createBuilder();
        $builder->addAuthors($this->parseAuthors($details));
        if (isset($details['license'])) {
            $builder->setLicense($details['license']);
        }
        if (isset($details['description'])) {
            $builder->setDescription($details['description']);
        }
        if (isset($details['thumbnail'])) {
            $builder->setThumbnail($details['thumbnail']);
        }
        if (isset($details['screen'])) {
            $builder->setScreen($details['screen']);
        }
        if (isset($details['name'])) {
            $builder->setName($details['name']);
        }
        if (isset($details['version'])) {
            $builder->setVersion($details['version']);
        }

        try {
            return $builder->getDetails();
        } catch (\LogicException $e) {
            throw new \RuntimeException('An exception has occurred while parsing the details node, see the previous exception.', null, $e);
        }
    }

    /**
     * Parses an authors array
     *
     * @param array $collection A collection of properties
     *
     * @return Author[]
     *
     * @throws \RuntimeException         When an author definition has missing name and email
     * @throws \InvalidArgumentException If the "authors" is not an array
     * @throws \InvalidArgumentException If the "author" has unrecognized keys
     */
    private function parseAuthors(array $collection)
    {
        $authors = array();
        if (isset($collection['authors'])) {
            if (!is_array($collection['authors'])) {
                throw new \InvalidArgumentException('The "authors" element should be an array.');
            }

            foreach ($collection['authors'] as $author) {
                if (!is_array($author)) {
                    throw new \InvalidArgumentException('The "author" property should be an array.');
                } elseif ($diff = array_diff(array_keys($author), array('website', 'name', 'email'))) {
                    throw new \InvalidArgumentException(sprintf(
                        'The "author" element is invalid, the following keys are unrecognized: "%s".',
                        implode(', ', $diff)
                    ));
                } elseif (!isset($author['name']) || !isset($author['email'])) {
                    throw new \RuntimeException('The author name and email are required if you are defining the "author" element.');
                }

                $authors[] = new Author($author['name'], $author['email'], isset($author['website']) ? $author['website'] : null);
            }
        }

        return $authors;
    }

    /**
     * Parses tags specification
     *
     * @param array $specification A specification
     *
     * @return TagCollection
     */
    private function parseTags(array $specification)
    {
        $tags = array();
        if (isset($specification['tags'])) {
            foreach ($specification['tags'] as $tagName => $tagArguments) {
                $tags[] = $this->parseTag($tagName, $tagArguments);
            }
        }

        return new TagCollection($tags);
    }

    /**
     * Parses a tag
     *
     * @param string $name      A name
     * @param mixed  $arguments Arguments
     *
     * @return TagInterface
     *
     * @throws \InvalidArgumentException If tag definition is wrong
     * @throws \RuntimeException         When tag is not exist
     */
    private function parseTag($name, $arguments)
    {
        if (is_array($arguments)) {
            array_walk_recursive($arguments, array($this, 'replaceByPhpValue'));
            $this->replaceParameters($arguments);
        } else { // scalar value
            $this->replaceByPhpValue($arguments);
        }

        return $this->tagFactory->create($name, $arguments);
    }

    /**
     * Replaces parameters hooks with their values
     *
     * @param array $arguments Arguments
     *
     * @return void
     *
     * @throws \InvalidArgumentException When a parameter is not defined in the theme mapping file
     */
    private function replaceParameters(array &$arguments)
    {
        foreach ($arguments as &$arg) {
            if (is_array($arg)) {
                $this->replaceParameters($arg);
            } elseif (preg_match('/^%([^%]+)%$/', $arg, $matches)) {
                $realArg = $matches[1];
                if (!isset($this->parameters[$realArg])) {
                    throw new \InvalidArgumentException(sprintf('The parameter "%s" is not defined in the theme mapping file.', $realArg));
                }

                $arg = $this->parameters[$realArg];
            }
        }
    }

    /**
     * Replaces a given argument by its proper php value
     *
     * @param string $argument An argument
     *
     * @return void
     */
    private function replaceByPhpValue(&$argument)
    {
        // check if is a constant
        if (0 === strpos($argument, 'const@')) {
            $argument = $this->helper->resolveConstant(substr($argument, 6));
        }
    }

    /**
     * Validates an entire mapping file
     *
     * @param array  $content YAML file content
     * @param string $file    A mapping file
     *
     * @return void
     *
     * @throws \InvalidArgumentException When themes node is not defined
     * @throws \UnexpectedValueException When a content from the YAML file returns other data type than array
     */
    private function validate(array $content, $file)
    {
        if (!is_array($content)) { // Or a file has an illegal type
            throw new \UnexpectedValueException(sprintf('The return value must be of the YAML array type in the theme mapping file "%s".', $file));
        } elseif (!array_key_exists('themes', $content)) {
            throw new \InvalidArgumentException(sprintf('There is missing "themes" node in the theme mapping file "%s".', $file));
        }
    }
}
