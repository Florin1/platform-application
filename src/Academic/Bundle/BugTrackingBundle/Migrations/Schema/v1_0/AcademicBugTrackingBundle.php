<?php

namespace Academic\Bundle\BugTrackingBundle\Migrations\Schema\v1_0;

use Doctrine\DBAL\Schema\Schema;
use Oro\Bundle\MigrationBundle\Migration\Migration;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;
use Oro\Bundle\EntityExtendBundle\Migration\Extension\ExtendExtension;
use Oro\Bundle\EntityExtendBundle\Migration\Extension\ExtendExtensionAwareInterface;
use Oro\Bundle\EntityExtendBundle\EntityConfig\ExtendScope;
use Oro\Bundle\NoteBundle\Migration\Extension\NoteExtension;
use Oro\Bundle\NoteBundle\Migration\Extension\NoteExtensionAwareInterface;
use Oro\Bundle\ActivityBundle\Migration\Extension\ActivityExtension;
use Oro\Bundle\ActivityBundle\Migration\Extension\ActivityExtensionAwareInterface;
use Academic\Bundle\BugTrackingBundle\Entity\Issue;

class AcademicBugTrackingBundle implements
    Migration,
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
    public function up(Schema $schema, QueryBag $queries)
    {
        self::createOroAcademicIssueTable($schema);
        self::createOroAcademicIssuesCollaboratorsTable($schema);
        self::addOroAcademicIssueForeignKeys($schema);
        self::addOroAcademicIssuesCollaboratorsForeignKeys($schema);
        self::addEnums($schema, $this->extendExtension);
        self::addNote($schema, $this->noteExtension);
        self::addActivityAssociations($schema, $this->activityExtension);
    }

    /**
     * Create oro_academic_issue table
     *
     * @param Schema $schema
     */
    public static function createOroAcademicIssueTable(Schema $schema)
    {
        $table = $schema->createTable('oro_academic_issue');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('parent_id', 'integer', ['notnull' => false]);
        $table->addColumn('user_id', 'integer', ['notnull' => false]);
        $table->addColumn('serialized_data', 'array', ['notnull' => false, 'comment' => '(DC2Type:array)']);
        $table->addColumn('summary', 'string', ['length' => 255]);
        $table->addColumn('code', 'string', ['length' => 255]);
        $table->addColumn('description', 'text', []);
        $table->addColumn('createdAt', 'datetime', []);
        $table->addColumn('updatedAt', 'datetime', []);
        $table->setPrimaryKey(['id']);
        $table->addIndex(['user_id'], 'IDX_9CC307EFA76ED395', []);
        $table->addIndex(['parent_id'], 'IDX_9CC307EF727ACA70', []);
    }

    /**
     * Add oro_academic_issue foreign keys.
     *
     * @param Schema $schema
     */
    public static function addOroAcademicIssueForeignKeys(Schema $schema)
    {
        $table = $schema->getTable('oro_academic_issue');
        $table->addForeignKeyConstraint(
            $schema->getTable('oro_academic_issue'),
            ['parent_id'],
            ['id'],
            ['onDelete' => null, 'onUpdate' => null]
        );
        $table->addForeignKeyConstraint(
            $schema->getTable('oro_user'),
            ['user_id'],
            ['id'],
            ['onDelete' => 'SET NULL', 'onUpdate' => null]
        );
    }

    /**
     * Create oro_academic_issues_collaborators table
     *
     * @param Schema $schema
     */
    public static function createOroAcademicIssuesCollaboratorsTable(Schema $schema)
    {
        $table = $schema->createTable('oro_academic_issue_user');
        $table->addColumn('issue_id', 'integer', []);
        $table->addColumn('user_id', 'integer', []);
        $table->setPrimaryKey(['issue_id', 'user_id']);
        $table->addIndex(['issue_id'], 'IDX_8C0DE655E7AA58C', []);
        $table->addIndex(['user_id'], 'IDX_8C0DE65A76ED395', []);
    }

    /**
     * Add oro_academic_issues_collaborators foreign keys.
     *
     * @param Schema $schema
     */
    public static function addOroAcademicIssuesCollaboratorsForeignKeys(Schema $schema)
    {
        $table = $schema->getTable('oro_academic_issue_user');
        $table->addForeignKeyConstraint(
            $schema->getTable('oro_user'),
            ['user_id'],
            ['id'],
            ['onDelete' => null, 'onUpdate' => null]
        );
        $table->addForeignKeyConstraint(
            $schema->getTable('oro_academic_issue'),
            ['issue_id'],
            ['id'],
            ['onDelete' => null, 'onUpdate' => null]
        );
    }

    /**
     * @param Schema $schema
     * @param ExtendExtension $extendExtension
     */
    public static function addEnums(Schema $schema, ExtendExtension $extendExtension)
    {
        $table = $schema->getTable('oro_academic_issue');

        $extendExtension->addEnumField(
            $schema,
            $table,
            'type',
            Issue::TYPE_ENUM_CODE,
            false,
            false,
            [
                'extend' => ['owner' => ExtendScope::OWNER_CUSTOM]
            ]
        );

        $extendExtension->addEnumField(
            $schema,
            $table,
            'priority',
            Issue::PRIORITY_ENUM_CODE,
            false,
            false,
            [
                'extend' => ['owner' => ExtendScope::OWNER_CUSTOM]
            ]
        );

        $extendExtension->addEnumField(
            $schema,
            $table,
            'resolution',
            Issue::RESOLUTION_ENUM_CODE,
            false,
            false,
            [
                'extend' => ['owner' => ExtendScope::OWNER_CUSTOM]
            ]
        );
    }

    /**
     * @param Schema $schema
     * @param NoteExtension $noteExtension
     */
    public static function addNote(Schema $schema, NoteExtension $noteExtension)
    {
        $noteExtension->addNoteAssociation($schema, 'oro_academic_issue');
    }

    /**
     * @param Schema $schema
     * @param ActivityExtension $activityExtension
     */
    public static function addActivityAssociations(Schema $schema, ActivityExtension $activityExtension)
    {
        $activityExtension->addActivityAssociation($schema, 'oro_email', 'oro_academic_issue', true);
    }
}
