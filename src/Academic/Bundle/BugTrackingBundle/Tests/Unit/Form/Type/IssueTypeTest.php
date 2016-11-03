<?php

namespace Academic\Bundle\BugTrackingBundle\Tests\Unit\Form\Type;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\FormIntegrationTestCase;

use Oro\Bundle\UserBundle\Entity\User;

use Academic\Bundle\BugTrackingBundle\Tests\Unit\Form\EventListener\Stub\IssueSubscriberStub;
use Academic\Bundle\BugTrackingBundle\Tests\Unit\Form\Type\Stub\IssueStub;
use Academic\Bundle\BugTrackingBundle\Tests\Unit\Form\Type\Stub\EnumSelectTypeStub;
use Academic\Bundle\BugTrackingBundle\Form\Type\IssueType;
use Academic\Bundle\BugTrackingBundle\Form\EventListener\IssueSubscriber;
use Academic\Bundle\BugTrackingBundle\Entity\Issue;

class IssueTypeTest extends FormIntegrationTestCase
{
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
     * @var OptionsResolverInterface|\PHPUnit_Framework_MockObject_MockObject $resolver
     */
    protected $resolver;

    /**
     * @var Issue
     */
    protected $issue;

    /**
     * @return array
     */
    protected function getExtensions()
    {
        $stubEnumSelectType = new EnumSelectTypeStub([]);

        return [
            new PreloadedExtension(
                [
                    $stubEnumSelectType->getName() => $stubEnumSelectType,
                ],
                []
            )
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        parent::setUp();

        $this->eventListener = $this->getMockBuilder(IssueSubscriber::class)
            ->disableOriginalConstructor()
            ->setMethods(['getSubscribedEvents'])
            ->getMock();
        $this->eventListener->expects($this->any())
            ->method('getSubscribedEvents')
            ->willReturn([]);
        $this->resolver = $this->getMock(OptionsResolverInterface::class);

        $this->resolver->expects($this->any())
            ->method('setDefaults')
            ->with($this->isType('array'));

        $this->issueType = new IssueType(Issue::class, new IssueSubscriberStub());
    }

    public function testSetDefaultOptions()
    {
        $this->issueType->setDefaultOptions($this->resolver);
    }

    public function testGetName()
    {
        $this->assertEquals('academic_bug_tracking_issue', $this->issueType->getName());
    }

    /**
     * @dataProvider submitDataProvider
     * @param Issue $defaultData
     * @param array $submittedData
     * @param Issue $expectedData
     */
    public function testSubmit(Issue $defaultData, array $submittedData, Issue $expectedData)
    {
        $form = $this->factory->create($this->issueType, $defaultData, []);
        $this->assertEquals($defaultData, $form->getData());
        $form->submit($submittedData);
        $this->assertTrue($form->isValid());

        $data = $form->getData();

        $this->assertEquals($expectedData, $data);
    }

    /**
     * @return array
     */
    public function submitDataProvider()
    {
        $expectedIssue = $this->getExpectedIssue();
        $defaultIssue = $this->getDefaultIssue();

        return [
            'issue test' => [
                'defaultData' => $defaultIssue,
                'submittedData' => [
                    'code' => 'code',
                    'description' => 'description',
                    'summary' => 'summary',
                    'type' => Issue::TYPE_STORY,
                    'priority' => Issue::PRIORITY_BLOCKER,
                    'resolution' => Issue::RESOLUTION_DONE,
                    'assignee' => 1,
                    'reporter' => 1,
                ],
                'expectedData' => $expectedIssue
            ]
        ];
    }

    public function getDefaultIssue()
    {
        $issue = new IssueStub();
        $issue->setAssignee($this->getUser());
        $issue->setReporter($this->getUser());

        return $issue;
    }

    public function getExpectedIssue()
    {
        $issue = new IssueStub;
        $issue->setCode('code');
        $issue->setDescription('description');
        $issue->setSummary('summary');
        $issue->setAssignee($this->getUser());
        $issue->setReporter($this->getUser());

        return $issue;
    }

    public function getUser()
    {
        $user = new User();
        $user->setSalt('salt');
        $user->setId(1);

        return $user;
    }
}
