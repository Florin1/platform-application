<?php

namespace Academic\Bundle\BugTrackingBundle\Migrations\Data\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

use Oro\Bundle\DashboardBundle\Migrations\Data\ORM\AbstractDashboardFixture;
use Oro\Bundle\UserBundle\Migrations\Data\ORM\LoadAdminUserData;
use Oro\Bundle\DashboardBundle\Migrations\Data\ORM\LoadDashboardData as DashboardData;

class LoadDashboardData extends AbstractDashboardFixture implements DependentFixtureInterface
{
    /**
     * {@inheritdoc}
     */
    public function getDependencies()
    {
        return [
            LoadAdminUserData::class,
            DashboardData::class,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $dashboard = $this->findAdminDashboardModel(
            $manager,
            'main'
        );

        $dashboard
            ->setIsDefault(true)
            ->setLabel(
                $this->container->get('translator')->trans('oro.dashboard.title.main')
            )
            ->addWidget(
                $this->createWidgetModel(
                    'recent_issues',
                    [
                        0,
                        10
                    ]
                )
            )
            ->addWidget(
                $this->createWidgetModel(
                    'issues_chart',
                    [
                        0,
                        10
                    ]
                )
            );

        $manager->flush();
    }
}
