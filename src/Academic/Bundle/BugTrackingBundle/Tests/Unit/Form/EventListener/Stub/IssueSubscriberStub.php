<?php

namespace Academic\Bundle\BugTrackingBundle\Tests\Unit\Form\EventListener\Stub;

use Academic\Bundle\BugTrackingBundle\Form\EventListener\IssueSubscriber;

class IssueSubscriberStub extends IssueSubscriber
{
    /**
     * @inheritDoc
     */
    public function __construct()
    {
    }

    /**
     * @inheritDoc
     */
    public static function getSubscribedEvents()
    {
        return [];
    }
}
