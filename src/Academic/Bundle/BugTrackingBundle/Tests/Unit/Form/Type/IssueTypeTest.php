<?php

namespace Academic\Bundle\BugTrackingBundle\Tests\Unit\Form\Type;

use Symfony\Component\Form\FormBuilder;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Academic\Bundle\BugTrackingBundle\Form\Type\IssueType;
use Academic\Bundle\BugTrackingBundle\Form\EventListener\IssueSubscriber;
use Academic\Bundle\BugTrackingBundle\Entity\Issue;

class IssueTypeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var |\PHPUnit_Framework_MockObject_MockObject
     */
    protected $dataClass;

    /**
     * @var IssueSubscriber|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $eventListener;

    /**
     * @var IssueType
     */
    protected $issueType;

    /**
     * @var IssueSubscriber|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $issueSubscriber;

    /**
     * @var FormBuilder|\PHPUnit_Framework_MockObject_MockObject $builder
     */
    protected $builder;

    /**
     * @var OptionsResolverInterface|\PHPUnit_Framework_MockObject_MockObject $resolver
     */
    protected $resolver;

    /**
     * @var Issue|\PHPUnit_Framework_MockObject_MockObject $issue
     */
    protected $issue;

    protected function setUp()
    {
        $this->issue = $this->getMockBuilder(Issue::class)
            ->disableOriginalConstructor()
            ->setMethods(array('hasParent'))
            ->getMock();

        $this->issue->expects($this->any())
            ->method('hasParent')
            ->willReturn(false);

        $this->eventListener = $this->getMockBuilder(IssueSubscriber::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->builder = $this->getMockBuilder(FormBuilder::class)
            ->disableOriginalConstructor()
            ->setMethods(array('add', 'addEventSubscriber', 'getData'))
            ->getMock();

        $this->builder->expects($this->any())
            ->method('add')
            ->willReturn($this->builder);

        $this->builder->expects($this->any())
            ->method('getData')
            ->willReturn($this->issue);

        $this->builder->expects($this->any())
            ->method('addEventSubscriber')
            ->with($this->isInstanceOf(IssueSubscriber::class));

        $this->resolver = $this->getMock(OptionsResolverInterface::class);

        $this->resolver->expects($this->any())
            ->method('setDefaults')
            ->with($this->isType('array'));

        $this->issueType = new IssueType($this->dataClass, $this->eventListener);
    }

    public function testBuildForm()
    {
        $this->issueType->buildForm($this->builder, []);
    }

    public function testSetDefaultOptions()
    {
        $this->issueType->setDefaultOptions($this->resolver);
    }

    public function testGetName()
    {
        $this->assertEquals('academic_bug_tracking_issue', $this->issueType->getName());
    }
}
