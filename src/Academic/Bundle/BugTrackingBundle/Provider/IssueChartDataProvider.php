<?php

namespace Academic\Bundle\BugTrackingBundle\Provider;

use Oro\Bundle\EntityBundle\ORM\DoctrineHelper;
use Oro\Bundle\EntityExtendBundle\Tools\ExtendHelper;
use Oro\Bundle\EntityExtendBundle\Entity\Repository\EnumValueRepository;
use Academic\Bundle\BugTrackingBundle\Entity\Issue;
use Academic\Bundle\BugTrackingBundle\Entity\Repository\IssueRepository;

class IssueChartDataProvider
{
    /**
     * @var DoctrineHelper
     */
    protected $doctrineHelper;

    /**
     * Constructor
     */
    public function __construct(DoctrineHelper $doctrineHelper)
    {
        $this->doctrineHelper = $doctrineHelper;
    }

    /**
     * Gets issue chart data
     * @return array
     */
    public function getIssueChartData()
    {
        /** @var IssueRepository $issueRepository */
        $issueRepository = $this->getRepository(Issue::class);
        $groupedValues = $issueRepository->getIssuesGroupedByStatus();

        $entityName = ExtendHelper::buildEnumValueClassName('oac_issue_status');

        /** @var EnumValueRepository $enumRepository */
        $enumRepository = $this->getRepository($entityName);
        $existingStatuses = $enumRepository->findAll();
        $resultData = [];

        foreach ($existingStatuses as $status) {
            $resultData[$status->getId()]['status'] = $status->getName();
            $resultData[$status->getId()]['count'] = 0;
        }

        foreach ($groupedValues as $group) {
            $resultData[end($group)]['count'] = reset($group);
        }

        return array_values($resultData);
    }

    /**
     * Gets repository off class
     *
     * @param $class
     * @return \Doctrine\ORM\EntityRepository
     */
    public function getRepository($class)
    {
        return $this->doctrineHelper->getEntityRepositoryForClass($class);
    }
}
