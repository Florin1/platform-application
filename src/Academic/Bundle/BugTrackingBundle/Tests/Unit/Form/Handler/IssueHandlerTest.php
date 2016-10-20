<?php

namespace Academic\Bundle\BugTrackingBundle\Tests\Unit\Form\Handler;

use Symfony\Component\HttpFoundation\Request;

use Oro\Bundle\EntityBundle\ORM\DoctrineHelper;
use Oro\Bundle\EntityBundle\Tools\EntityRoutingHelper;
use Oro\Bundle\EntityExtendBundle\Entity\Repository\EnumValueRepository;
use Oro\Bundle\EntityExtendBundle\Entity\AbstractEnumValue;

use Academic\Bundle\BugTrackingBundle\Entity\Repository\IssueRepository;
use Academic\Bundle\BugTrackingBundle\Entity\Issue;
use Academic\Bundle\BugTrackingBundle\Form\Handler\IssueHandler;

class IssueHandlerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Request|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $request;

    /**
     * @var DoctrineHelper|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $doctrineHelper;

    /**
     * @var EntityRoutingHelper|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $entityRoutingHelper;

    /**
     * @var IssueHandler
     */
    protected $issueHandler;

    /**
     * @var IssueRepository|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $issueRepo;
    /**
     * @var EnumValueRepository|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $enumRepo;
    /**
     * @var AbstractEnumValue|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $enumEntity;

    /**
     * @var Issue|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $issue;


    protected function setUp()
    {
        $this->request = $this->getMockBuilder(Request::class)
            ->disableOriginalConstructor()
            ->setMethods(array('get'))
            ->getMock();

        $this->request->expects($this->any())
            ->method('get')
            ->will($this->onConsecutiveCalls(1, Issue::class, Issue::class));

        $this->issue = $this->getMockBuilder(Issue::class)
            ->disableOriginalConstructor()
            ->setMethods(array('setType', 'setParent'))
            ->getMock();

        $this->request->expects($this->any())
            ->method('setType');

        $this->request->expects($this->any())
            ->method('setParent');

        $this->enumEntity = $this->getMockBuilder(AbstractEnumValue::class)
            ->disableOriginalConstructor()
            ->setMethods(array('getId', 'getName'))
            ->getMock();

        $this->enumEntity->expects($this->any())
            ->method('getId')
            ->willReturn('open');

        $this->enumEntity->expects($this->any())
            ->method('getName')
            ->willReturn('Open');

        $this->issueRepo = $this->getMockBuilder(IssueRepository::class)
            ->disableOriginalConstructor()
            ->setMethods(array('findOneBy'))
            ->getMock();

        $this->issueRepo->expects($this->any())
            ->method('findOneBy')
            ->willReturn($this->issue);

        $this->enumRepo = $this->getMockBuilder(EnumValueRepository::class)
            ->disableOriginalConstructor()
            ->setMethods(array('findOneBy'))
            ->getMock();

        $this->enumRepo->expects($this->any())
            ->method('findOneBy')
            ->willReturn($this->enumEntity);

        $this->doctrineHelper = $this->getMockBuilder(DoctrineHelper::class)
            ->disableOriginalConstructor()
            ->setMethods(array('getEntityRepositoryForClass'))
            ->getMock();

        $this->doctrineHelper->expects($this->any())
            ->method('getEntityRepositoryForClass')
            ->will($this->onConsecutiveCalls($this->enumRepo, $this->issueRepo));

        $this->entityRoutingHelper = $this->getMockBuilder(EntityRoutingHelper::class)
            ->disableOriginalConstructor()
            ->setMethods(array('decodeClassName'))
            ->getMock();

        $this->entityRoutingHelper->expects($this->any())
            ->method('decodeClassName')
            ->willReturn(Issue::class);

        $this->issueHandler = new IssueHandler($this->request, $this->doctrineHelper, $this->entityRoutingHelper);
    }

    public function testUpdateIssue()
    {
        $this->assertInstanceOf(Issue::class, $this->issueHandler->updateIssue($this->issue));
    }

    public function testGetRepository()
    {
        $this->doctrineHelper = $this->getMockBuilder(DoctrineHelper::class)
            ->disableOriginalConstructor()
            ->setMethods(array('getEntityRepositoryForClass'))
            ->getMock();

        $this->doctrineHelper->expects($this->any())
            ->method('getEntityRepositoryForClass')
            ->will($this->onConsecutiveCalls($this->issueRepo, $this->enumRepo));

        $this->issueHandler = new IssueHandler($this->request, $this->doctrineHelper, $this->entityRoutingHelper);
        $issueRepository = $this->issueHandler->getRepository(Issue::class);

        $this->assertInstanceOf(IssueRepository::class, $issueRepository);
    }
}
