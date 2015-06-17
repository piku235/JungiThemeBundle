<?php

/*
 * This file is part of the JungiThemeBundle package.
 *
 * (c) Piotr Kugla <piku235@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jungi\Bundle\ThemeBundle\Tests\Mapping\Processor;

use Jungi\Bundle\ThemeBundle\Mapping\Processor\VirtualThemeWorker;
use Jungi\Bundle\ThemeBundle\Tests\TestCase;

/**
 * VirtualThemeWorker Test Case.
 *
 * @author Piotr Kugla <piku235@gmail.com>
 */
class VirtualThemeWorkerTest extends TestCase
{
    /**
     * @var VirtualThemeWorker
     */
    private $worker;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->worker = new VirtualThemeWorker();
    }

    public function testWorkingExamples()
    {
    }
}
