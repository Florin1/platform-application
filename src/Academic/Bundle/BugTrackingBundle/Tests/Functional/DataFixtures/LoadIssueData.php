<?php

namespace Academic\Bundle\BugTrackingBundle\Tests\Functional\DataFixtures;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

use Oro\Bundle\EntityExtendBundle\Entity\Repository\EnumValueRepository;
use Oro\Bundle\EntityExtendBundle\Tools\ExtendHelper;
use Oro\Bundle\UserBundle\Entity\User;
use Oro\Bundle\UserBundle\Migrations\Data\ORM\LoadAdminUserData;

use Academic\Bundle\BugTrackingBundle\Entity\Issue;

class LoadIssueData extends AbstractFixture
{
    /**
     * {@inheritdoc}
     */
    public function getDependencies()
    {
        return [
            LoadAdminUserData::class,
        ];
    }

    /**
     * Loads issues fixtures
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $priority = $this->getEnumsData($manager, Issue::PRIORITY_ENUM_CODE, ['default' => 1]);
        $type = $this->getEnumsData($manager, Issue::TYPE_ENUM_CODE, ['default' => 1]);
        $resolution = $this->getEnumsData($manager, Issue::RESOLUTION_ENUM_CODE, ['default' => 1]);
        $status = $this->getEnumsData($manager, Issue::STATUS_ENUM_CODE, ['default' => 1]);
        $user = $manager->getRepository(User::class)->findOneBy(['enabled' => true]);

        $issue = new Issue();
        $issue->setCode('code');
        $issue->setSummary('summary');
        $issue->setDescription('description');
        $issue->setAssignee($user);
        $issue->setReporter($user);
        $issue->setPriority($priority);
        $issue->setType($type);
        $issue->setStatus($status);
        $issue->setResolution($resolution);

        $manager->persist($issue);
        $manager->flush();

        $this->addReference('issue', $issue);
    }

    /**
     * @param ObjectManager $manager
     * @param $enumCode
     * @param array $params
     * @return null|object
     */
    protected function getEnumsData(ObjectManager $manager, $enumCode, $params = [])
    {
        $entityName = ExtendHelper::buildEnumValueClassName($enumCode);

        /** @var EnumValueRepository $enumRepository */
        $enumRepository = $manager->getRepository($entityName);

        return $enumRepository->findOneBy($params);
    }
}
