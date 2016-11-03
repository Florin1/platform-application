<?php

namespace Academic\Bundle\BugTrackingBundle\Tests\Unit\Form\EventListener;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\Test\FormIntegrationTestCase;
use Symfony\Component\Form\Form;

use Oro\Bundle\EntityBundle\ORM\DoctrineHelper;
use Oro\Bundle\UserBundle\Entity\Repository\UserRepository;

use Academic\Bundle\BugTrackingBundle\Form\EventListener\IssueSubscriber;
use Academic\Bundle\BugTrackingBundle\Entity\Issue;

class IssueSubscriberTest extends FormIntegrationTestCase
{
    /**
     * @var IssueSubscriber
     */
    protected $issueSubscriber;

    /**
     * @var FormEvent|\PHPUnit_Framework_MockObject_MockObject $event
     */
    protected $event;

    /**
     * @var Form|\PHPUnit_Framework_MockObject_MockObject $form
     */
    protected $form;

    /**
     * @var Form|\PHPUnit_Framework_MockObject_MockObject $issue
     */
    protected $issue;

    /**
     * @var DoctrineHelper|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $doctrineHelper;

    /**
     * @var UserRepository|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $userRepo;

    protected function setUp()
    {
        parent::setUp();

        $this->userRepo = $this->getMockBuilder(UserRepository::class)
            ->disableOriginalConstructor()
            ->setMethods(['findBy'])
            ->getMock();

        $this->userRepo->expects($this->any())
            ->method('findBy')
            ->willReturn([]);

        $this->doctrineHelper = $this->getMockBuilder(DoctrineHelper::class)
            ->disableOriginalConstructor()
            ->setMethods(['getEntityRepositoryForClass'])
            ->getMock();

        $this->doctrineHelper->expects($this->any())
            ->method('getEntityRepositoryForClass')
            ->willReturn($this->userRepo);

        $this->issue = $this->getMock(Issue::class);

        $this->form = $this->getMockBuilder(Form::class)
            ->disableOriginalConstructor()
            ->setMethods(['add'])
            ->getMock();

        $this->form->expects($this->any())
            ->method('add')
            ->willReturn($this->form);

        $this->event = $this->getMockBuilder(FormEvent::class)
            ->disableOriginalConstructor()
            ->setMethods(['getData', 'getForm'])
            ->getMock();

        $this->event->expects($this->any())
            ->method('getData')
            ->willReturn($this->issue);

        $this->event->expects($this->any())
            ->method('getForm')
            ->willReturn($this->form);

        $this->issueSubscriber = new IssueSubscriber($this->factory, $this->doctrineHelper);
    }

    public function testGetSubscribedEvents()
    {
        $this->assertEquals(
            [
                FormEvents::PRE_SET_DATA => 'preSetData',
            ],
            $this->issueSubscriber->getSubscribedEvents()
        );
    }

    public function testPreSetData()
    {
        $this->issueSubscriber->preSetData($this->event);
    }
}
