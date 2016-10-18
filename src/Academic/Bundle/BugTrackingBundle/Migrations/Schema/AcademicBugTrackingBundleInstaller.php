<?php

namespace Academic\Bundle\BugTrackingBundle\Migrations\Schema;

use Doctrine\DBAL\Schema\Schema;

use Oro\Bundle\MigrationBundle\Migration\Installation;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;
use Oro\Bundle\EntityExtendBundle\Migration\Extension\ExtendExtension;
use Oro\Bundle\EntityExtendBundle\Migration\Extension\ExtendExtensionAwareInterface;
use Oro\Bundle\NoteBundle\Migration\Extension\NoteExtension;
use Oro\Bundle\NoteBundle\Migration\Extension\NoteExtensionAwareInterface;
use Oro\Bundle\ActivityBundle\Migration\Extension\ActivityExtension;
use Oro\Bundle\ActivityBundle\Migration\Extension\ActivityExtensionAwareInterface;

use Academic\Bundle\BugTrackingBundle\Migrations\Schema\v1_0\AcademicBugTrackingBundle;
use Academic\Bundle\BugTrackingBundle\Migrations\Schema\v1_1\AcademicBugTrackingBundle as AcademicBugTrackingBundle11;

class AcademicBugTrackingBundleInstaller implements
    Installation,
    ExtendExtensionAwareInterface,
    NoteExtensionAwareInterface,
    ActivityExtensionAwareInterface
{
    /** @var ExtendExtension $extendExtension */
    protected $extendExtension;

    /** @var NoteExtension */
    protected $noteExtension;

    /** @var ActivityExtension */
    protected $activityExtension;

    /**
     * {@inheritdoc}
     */
    public function setActivityExtension(ActivityExtension $activityExtension)
    {
        $this->activityExtension = $activityExtension;
    }

    /**
     * {@inheritdoc}
     */
    public function setNoteExtension(NoteExtension $noteExtension)
    {
        $this->noteExtension = $noteExtension;
    }

    /**
     * {@inheritdoc}
     */
    public function setExtendExtension(ExtendExtension $extendExtension)
    {
        $this->extendExtension = $extendExtension;
    }

    /**
     * @inheritdoc
     */
    public function getMigrationVersion()
    {
        return 'v1_1';
    }

    /**
     * @inheritdoc
     */
    public function up(Schema $schema, QueryBag $queries)
    {
        AcademicBugTrackingBundle::createOroAcademicIssueTable($schema);
        AcademicBugTrackingBundle::createOroAcademicIssueUserTable($schema);
        AcademicBugTrackingBundle::addOroAcademicIssueForeignKeys($schema);
        AcademicBugTrackingBundle::addOroAcademicIssueUserForeignKeys($schema);
        AcademicBugTrackingBundle::addEnums($schema, $this->extendExtension);
        AcademicBugTrackingBundle::addNote($schema, $this->noteExtension);
        AcademicBugTrackingBundle::addActivityAssociations($schema, $this->activityExtension);

        AcademicBugTrackingBundle11::addStatusEnums($schema, $this->extendExtension);
    }
}
