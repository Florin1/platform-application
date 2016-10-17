<?php

namespace Academic\Bundle\BugTrackingBundle\Form\Handler;

use Symfony\Component\HttpFoundation\Request;
use Oro\Bundle\EntityBundle\ORM\DoctrineHelper;
use Oro\Bundle\UserBundle\Entity\User;
use Oro\Bundle\EntityBundle\Tools\EntityRoutingHelper;
use Oro\Bundle\EntityExtendBundle\Tools\ExtendHelper;
use Oro\Bundle\EntityExtendBundle\Entity\Repository\EnumValueRepository;
use Academic\Bundle\BugTrackingBundle\Entity\Issue;

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

    /** @var EntityRoutingHelper */
    protected $entityRoutingHelper;

    /**
     * IssueHandler constructor.
     * @param Request $request
     * @param DoctrineHelper $doctrineHelper
     * @param EntityRoutingHelper $entityRoutingHelper
     */
    public function __construct(
        Request $request,
        DoctrineHelper $doctrineHelper,
        EntityRoutingHelper $entityRoutingHelper
    ) {
        $this->request = $request;
        $this->doctrineHelper = $doctrineHelper;
        $this->entityRoutingHelper = $entityRoutingHelper;
    }

    /**
     * @param Issue $issue
     * @return Issue
     */
    public function updateIssue(Issue $issue)
    {
        $entityId = $this->request->get('entityId');
        $entityClass = $this->request->get('entityClass') ?
            $this->entityRoutingHelper->decodeClassName($this->request->get('entityClass')) :
            null;

        if ($entityId && $entityClass == User::class) {
            $user = $this->getRepository(User::class)->findOneBy(['id' => $entityId]);
            !$user ?: $issue->setAssignee($user);
        }

        if ($entityId && $entityClass == Issue::class) {
            $entityEnumName = ExtendHelper::buildEnumValueClassName(Issue::TYPE_ENUM_CODE);
            /** @var EnumValueRepository $enumRepository */
            $enumRepository = $this->getRepository($entityEnumName);
            $subtaskEnum = $enumRepository->findOneBy(['id' => Issue::TYPE_SUBTASK]);
            $issue->setType($subtaskEnum);

            $parent = $this->getRepository(Issue::class)->findOneBy(['id' => $entityId]);
            $issue->setParent($parent);
        }

        return $issue;
    }

    public function getRepository($class)
    {
        return $this->doctrineHelper->getEntityRepositoryForClass($class);
    }
}
