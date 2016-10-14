<?php

namespace Academic\Bundle\BugTrackingBundle\Form\Handler;

use Symfony\Component\HttpFoundation\Request;
use Oro\Bundle\EntityBundle\ORM\DoctrineHelper;
use Academic\Bundle\BugTrackingBundle\Entity\Issue;
use Oro\Bundle\UserBundle\Entity\User;

class IssueHandler
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * @var DoctrineHelper
     */
    protected $doctrineHelper;

    /**
     * IssueHandler constructor.
     * @param Request $request
     * @param DoctrineHelper $doctrineHelper
     */
    public function __construct(Request $request, DoctrineHelper $doctrineHelper)
    {
        $this->request = $request;
        $this->doctrineHelper = $doctrineHelper;
    }

    /**
     * @param Issue $issue
     * @return Issue
     */
    public function updateIssue(Issue $issue)
    {
        if ($userId = $this->request->get('entityId')) {
            $user = $this->getRepository(User::class)->findOneBy(['id' => $userId]);
            !$user ?: $issue->setAssignee($user);
        }

        return $issue;
    }

    public function getRepository($class)
    {
        return $this->doctrineHelper->getEntityRepositoryForClass($class);
    }
}
