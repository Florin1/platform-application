<?php

namespace Academic\Bundle\BugTrackingBundle\Tests\Unit\Provider;

use Oro\Bundle\EntityBundle\ORM\DoctrineHelper;
use Oro\Bundle\EntityExtendBundle\Entity\Repository\EnumValueRepository;
use Oro\Bundle\EntityExtendBundle\Entity\AbstractEnumValue;

use Academic\Bundle\BugTrackingBundle\Provider\IssueChartDataProvider;
use Academic\Bundle\BugTrackingBundle\Entity\Repository\IssueRepository;
use Academic\Bundle\BugTrackingBundle\Entity\Issue;

class IssueChartDataProviderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var DoctrineHelper|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $doctrineHelper;
    /**
     * @var IssueChartDataProvider
     */
    protected $issueChartDataProvider;
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

    protected function setUp()
    {
        $this->enumEntity = $this->getMockBuilder(AbstractEnumValue::class)
            ->disableOriginalConstructor()
            ->setMethods(['getId', 'getName'])
            ->getMock();

        $this->enumEntity->expects($this->any())
            ->method('getId')
            ->willReturn('open');

        $this->enumEntity->expects($this->any())
            ->method('getName')
            ->willReturn('Open');

        $this->issueRepo = $this->getMockBuilder(IssueRepository::class)
            ->disableOriginalConstructor()
            ->setMethods(['getIssuesGroupedByStatus'])
            ->getMock();

        $this->issueRepo->expects($this->any())
            ->method('getIssuesGroupedByStatus')
            ->willReturn([]);

        $this->enumRepo = $this->getMockBuilder(EnumValueRepository::class)
            ->disableOriginalConstructor()
            ->setMethods(['findAll'])
            ->getMock();

        $this->enumRepo->expects($this->any())
            ->method('findAll')
            ->willReturn([$this->enumEntity]);

        $this->doctrineHelper = $this->getMockBuilder(DoctrineHelper::class)
            ->disableOriginalConstructor()
            ->setMethods(['getEntityRepositoryForClass'])
            ->getMock();

        $this->doctrineHelper->expects($this->any())
            ->method('getEntityRepositoryForClass')
            ->will($this->onConsecutiveCalls($this->issueRepo, $this->enumRepo));

        $this->issueChartDataProvider = new IssueChartDataProvider($this->doctrineHelper);
    }

    public function testGetIssueChartData()
    {
        $issueChartDataArray = $this->issueChartDataProvider->getIssueChartData();
        $this->assertArrayHasKey('status', reset($issueChartDataArray));
    }

    public function testGetRepository()
    {
        $issueRepository = $this->issueChartDataProvider->getRepository(Issue::class);
        $this->assertInstanceOf(IssueRepository::class, $issueRepository);
    }
}
