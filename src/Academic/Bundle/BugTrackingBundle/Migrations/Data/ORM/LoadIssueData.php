<?php

namespace Academic\Bundle\BugTrackingBundle\Migrations\Data\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Oro\Bundle\EntityExtendBundle\Entity\Repository\EnumValueRepository;
use Oro\Bundle\EntityExtendBundle\Tools\ExtendHelper;
use Oro\Bundle\EntityExtendBundle\Entity\AbstractEnumValue;
use Academic\Bundle\BugTrackingBundle\Entity\Issue;

class LoadIssueEnumsData extends AbstractFixture
{
    /** @var array */
    protected $priorityEnumData = [
        Issue::PRIORITY_BLOCKER => [
            'label' => 'Blocker',
            'priority' => 1,
            'default' => true
        ],
        Issue::PRIORITY_CRITICAL => [
            'label' => 'Critical',
            'priority' => 2,
            'default' => false
        ],
        Issue::PRIORITY_MAJOR => [
            'label' => 'Major',
            'priority' => 3,
            'default' => false
        ],
        Issue::PRIORITY_TRIVIAL => [
            'label' => 'Trivial',
            'priority' => 4,
            'default' => false
        ]
    ];

    /** @var array */
    protected $typeEnumData = [
        Issue::TYPE_TASK => [
            'label' => 'Task',
            'priority' => 1,
            'default' => true
        ],
        Issue::TYPE_SUBTASK => [
            'label' => 'Subtask',
            'priority' => 2,
            'default' => false
        ],
        Issue::TYPE_BUG => [
            'label' => 'Bug',
            'priority' => 3,
            'default' => false
        ],
    ];

    /** @var array */
    protected $resolutionEnumData = [
        Issue::RESOLUTION_FIXED => [
            'label' => 'Fixed',
            'priority' => 1,
            'default' => true
        ],
        Issue::RESOLUTION_DUPLICATE => [
            'label' => 'Duplicate',
            'priority' => 2,
            'default' => false
        ],
        Issue::RESOLUTION_INCOMPLETE => [
            'label' => 'Incomplete',
            'priority' => 3,
            'default' => false
        ],
        Issue::RESOLUTION_CANNOT_REPRODUCE => [
            'label' => 'Cannot reproduce',
            'priority' => 3,
            'default' => false
        ],
        Issue::RESOLUTION_DONE => [
            'label' => 'Done',
            'priority' => 3,
            'default' => false
        ],
    ];

    /** @var array */
    protected $statusEnumData = [
        Issue::STATUS_OPEN => [
            'label' => 'Open',
            'priority' => 1,
            'default' => true
        ],
        Issue::STATUS_IN_PROGRESS => [
            'label' => 'In progress',
            'priority' => 2,
            'default' => false
        ],
        Issue::STATUS_CLOSED => [
            'label' => 'Closed',
            'priority' => 3,
            'default' => false
        ],
        Issue::STATUS_RESOLVED => [
            'label' => 'Resolved',
            'priority' => 4,
            'default' => false
        ],
        Issue::STATUS_REOPENED => [
            'label' => 'Reopened',
            'priority' => 5,
            'default' => false
        ],
    ];

    public function load(ObjectManager $manager)
    {
        $this->loadData($manager, Issue::PRIORITY_ENUM_CODE, $this->priorityEnumData);
        $this->loadData($manager, Issue::TYPE_ENUM_CODE, $this->typeEnumData);
        $this->loadData($manager, Issue::RESOLUTION_ENUM_CODE, $this->resolutionEnumData);
        $this->loadData($manager, Issue::STATUS_ENUM_CODE, $this->statusEnumData);
    }

    /**
     * @param ObjectManager $manager
     * @param string $enumCode
     * @param array $data
     */
    protected function loadData(ObjectManager $manager, $enumCode, $data)
    {
        $entityName = ExtendHelper::buildEnumValueClassName($enumCode);

        /** @var EnumValueRepository $enumRepository */
        $enumRepository = $manager->getRepository($entityName);
        $existingValues = $enumRepository->findAll();
        $existingCodes = [];

        /** @var AbstractEnumValue $existingValue */
        foreach ($existingValues as $existingValue) {
            $existingCodes[$existingValue->getId()] = true;
        }

        foreach ($data as $key => $value) {
            if (!isset($existingCodes[$key])) {
                $enum = $enumRepository->createEnumValue(
                    $value['label'],
                    $value['priority'],
                    $value['default'],
                    $key
                );

                $existingCodes[$key] = true;
                $manager->persist($enum);
            }
        }

        $manager->flush();
    }
}
