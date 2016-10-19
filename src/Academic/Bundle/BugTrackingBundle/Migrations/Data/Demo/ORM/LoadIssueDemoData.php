<?php

namespace Academic\Bundle\BugTrackingBundle\Migrations\Data\Demo\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;

use Oro\Bundle\EntityExtendBundle\Entity\Repository\EnumValueRepository;
use Oro\Bundle\EntityExtendBundle\Tools\ExtendHelper;
use Oro\Bundle\UserBundle\Entity\User;

use Academic\Bundle\BugTrackingBundle\Entity\Issue;

class LoadIssueDemoData extends AbstractFixture
{
    /**
     * @var array
     */
    protected $priorities = [];
    /**
     * @var array
     */
    protected $types = [];
    /**
     * @var array
     */
    protected $resolutions = [];
    /**
     * @var array
     */
    protected $statuses = [];
    /**
     * @var array
     */
    protected $users = [];
    /**
     * @var string
     */
    protected $string = 'abcdefghijklmnopqrstuvwxyz';
    /**
     * @var string
     */
    protected $baseCode = 'INTAP-';
    /**
     * @var int
     */
    protected $issuesNumber = 30;

    /**
     * Loads issues fixtures
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $this->initData($manager);

        for ($i = 0; $i < $this->issuesNumber; $i++) {
            $issue = new Issue();
            $issue->setCode($this->baseCode . strval(rand(0, 100)));
            $issue->setSummary(str_shuffle($this->string));
            $issue->setDescription(str_shuffle($this->string));
            $issue->setAssignee($this->users[array_rand($this->users)]);
            $issue->setReporter($this->users[array_rand($this->users)]);
            $issue->setPriority($this->priorities[array_rand($this->priorities)]);
            $issue->setType($this->types[array_rand($this->types)]);
            $issue->setStatus($this->statuses[array_rand($this->statuses)]);
            $issue->setResolution($this->resolutions[array_rand($this->resolutions)]);
            $manager->persist($issue);

            if ($i % 10 == 0) {
                $manager->flush();
            }
        }
        $manager->flush();
    }

    /**
     * @param ObjectManager $manager
     */
    public function initData(ObjectManager $manager)
    {
        $this->priorities = $this->getEnumsData($manager, Issue::PRIORITY_ENUM_CODE);
        $this->types = $this->getEnumsData($manager, Issue::TYPE_ENUM_CODE, ['default' => 1]);
        $this->resolutions = $this->getEnumsData($manager, Issue::RESOLUTION_ENUM_CODE);
        $this->statuses = $this->getEnumsData($manager, Issue::STATUS_ENUM_CODE, ['default' => 1]);
        $this->users = $manager->getRepository(User::class)->findBy(['enabled' => true]);
    }

    /**
     * @param ObjectManager $manager
     * @param $enumCode
     * @param array $params
     * @return array
     */
    protected function getEnumsData(ObjectManager $manager, $enumCode, $params = [])
    {
        $entityName = ExtendHelper::buildEnumValueClassName($enumCode);

        /** @var EnumValueRepository $enumRepository */
        $enumRepository = $manager->getRepository($entityName);

        return $enumRepository->findBy($params);
    }
}
