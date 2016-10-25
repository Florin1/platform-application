<?php

namespace Academic\Bundle\BugTrackingBundle\Tests\Unit\Form\Type\Stub;

use Oro\Bundle\EntityExtendBundle\Entity\AbstractEnumValue;

use Academic\Bundle\BugTrackingBundle\Entity\Issue;

class IssueStub extends Issue
{
    /**
     * @var AbstractEnumValue
     */
    private $type;
    /**
     * @var AbstractEnumValue
     */
    private $resolution;
    /**
     * @var AbstractEnumValue
     */
    private $priority;

    /**
     * @return AbstractEnumValue
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param AbstractEnumValue $type
     * @return $this
     */
    public function setType(AbstractEnumValue $type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return AbstractEnumValue
     */
    public function getResolution()
    {
        return $this->resolution;
    }

    /**
     * @param AbstractEnumValue $resolution
     * @return $this
     */
    public function setResolution(AbstractEnumValue $resolution)
    {
        $this->resolution = $resolution;

        return $this;
    }

    /**
     * @return AbstractEnumValue
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * @param AbstractEnumValue $priority
     * @return $this
     */
    public function setPriority(AbstractEnumValue $priority)
    {
        $this->priority = $priority;

        return $this;
    }
}
