<?php

namespace Academic\Bundle\BugTrackingBundle\Migrations\Schema\v1_1;

use Doctrine\DBAL\Schema\Schema;

use Oro\Bundle\MigrationBundle\Migration\Migration;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;
use Oro\Bundle\EntityExtendBundle\Migration\Extension\ExtendExtension;
use Oro\Bundle\EntityExtendBundle\Migration\Extension\ExtendExtensionAwareInterface;
use Oro\Bundle\EntityExtendBundle\EntityConfig\ExtendScope;

use Academic\Bundle\BugTrackingBundle\Entity\Issue;

class AcademicBugTrackingBundle implements
    Migration,
    ExtendExtensionAwareInterface
{
    /** @var ExtendExtension $extendExtension */
    protected $extendExtension;

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
        self::addStatusEnums($schema, $this->extendExtension);
    }

    /**
     * @param Schema $schema
     * @param ExtendExtension $extendExtension
     */
    public static function addStatusEnums(Schema $schema, ExtendExtension $extendExtension)
    {
        $table = $schema->getTable('oro_academic_issue');

        $extendExtension->addEnumField(
            $schema,
            $table,
            'status',
            Issue::STATUS_ENUM_CODE,
            false,
            false,
            [
                'extend' => ['owner' => ExtendScope::OWNER_CUSTOM]
            ]
        );
    }
}
