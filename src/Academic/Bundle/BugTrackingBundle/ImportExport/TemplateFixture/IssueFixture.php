<?php

namespace Academic\Bundle\BugTrackingBundle\ImportExport\TemplateFixture;

use Academic\Bundle\BugTrackingBundle\Entity\Issue;
use Oro\Bundle\ImportExportBundle\TemplateFixture\AbstractTemplateRepository;
use Oro\Bundle\ImportExportBundle\TemplateFixture\TemplateFixtureInterface;
use Oro\Bundle\EntityExtendBundle\Tools\ExtendHelper;
use Oro\Bundle\UserBundle\Entity\User;

class IssueFixture extends AbstractTemplateRepository implements TemplateFixtureInterface
{
    /**
     * @return mixed
     */
    public function getEntityClass()
    {
        return Issue::class;
    }

    /**
     * @return \Iterator
     */
    public function getData()
    {
        return $this->getEntityData('example-task');
    }

    /**
     * @param string $key
     * @param Issue $entity
     */
    public function fillEntityData($key, $entity)
    {
        $user = new User;
        $user->setUsername('username');

        /** @var Issue $entity */
        $entity->setCode('INTAP-92');
        $entity->setSummary('Import/Export issue');
        $entity->setDescription('Create import/export functionality');
        $entity->setAssignee($user);
        $entity->setReporter($user);
        $entity->setPriority($this->getStubEnum(Issue::PRIORITY_ENUM_CODE, Issue::PRIORITY_MAJOR));
        $entity->setType($this->getStubEnum(Issue::TYPE_ENUM_CODE, Issue::TYPE_TASK));
        $entity->setResolution($this->getStubEnum(Issue::RESOLUTION_ENUM_CODE, Issue::RESOLUTION_DONE));
    }

    /**
     * @param string $key
     * @return Issue
     */
    protected function createEntity($key)
    {
        return new Issue();
    }

    /**
     * @param $enumCode
     * @param $enumType
     * @return mixed
     */
    protected function getStubEnum($enumCode, $enumType)
    {
        $entityName = ExtendHelper::buildEnumValueClassName($enumCode);
        $entity = new $entityName($enumType, ucfirst($enumType));

        return $entity;
    }
}
