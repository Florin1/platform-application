<?php

namespace Academic\Bundle\BugTrackingBundle\Tests\Unit\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;

use Academic\Bundle\BugTrackingBundle\DependencyInjection\AcademicBugTrackingExtension;

class AcademicBugTrackingExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var AcademicBugTrackingExtension
     */
    protected $academicBugTrackingExtension;

    protected function setUp()
    {
        $this->academicBugTrackingExtension = new AcademicBugTrackingExtension();
    }

    public function testLoad()
    {
        $configuration = new ContainerBuilder();
        $loader = new AcademicBugTrackingExtension();
        $loader->load([], $configuration);
        $this->assertTrue($configuration instanceof ContainerBuilder);
    }
}
