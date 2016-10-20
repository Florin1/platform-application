<?php

namespace Academic\Bundle\BugTrackingBundle\Tests\Unit\Entity;

use Oro\Bundle\UserBundle\Entity\User;
use Oro\Component\Testing\Unit\EntityTestCaseTrait;

use Academic\Bundle\BugTrackingBundle\Entity\Issue;

class IssueTest extends \PHPUnit_Framework_TestCase
{
    use EntityTestCaseTrait;

    public function testAccessors()
    {
        $this->assertPropertyAccessors(new Issue(), [
            ['id', 42],
            ['summary', 'some string'],
            ['code', 'some string'],
            ['description', 'some string'],
            ['createdAt', new \DateTime()],
            ['updatedAt', new \DateTime()],
            ['assignee', new User()],
            ['reporter', new User()]
        ]);
    }

    public function testToString()
    {
        $issue = new Issue();
        $issue->setCode('test_code');

        $this->assertEquals('test_code', (string)$issue);
    }

    public function testIsCollaborator()
    {
        $user = $this->getMockBuilder(User::class)
            ->disableOriginalConstructor()
            ->getMock();
        $issue = new Issue;
        $issue->addCollaborator($user);

        $this->assertTrue($issue->isCollaborator($user));
    }

    public function testHandleCollaborator()
    {
        $user = $this->getMockBuilder(User::class)
            ->disableOriginalConstructor()
            ->getMock();
        $issue = new Issue;
        $issue->handleCollaborator($user);

        $this->assertTrue($issue->isCollaborator($user));
    }

    public function testHasParent()
    {
        $issue = new Issue;

        $this->assertFalse($issue->hasParent());
    }
}
