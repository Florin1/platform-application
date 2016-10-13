<?php

namespace Academic\Bundle\BugTrackingBundle\Entity\Repository;

class IssueRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * Gets issues count grouped by status
     * @return array
     */
    public function getIssuesGroupedByStatus()
    {
        return $this->createQueryBuilder('i')
            ->select('COUNT(i), IDENTITY(i.status) as status')
            ->addGroupBy('i.status')
            ->getQuery()
            ->getResult();
    }
}
