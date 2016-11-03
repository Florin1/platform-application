<?php

namespace Academic\Bundle\BugTrackingBundle\Tests\Functional\Entity\Repository;

use Oro\Bundle\TestFrameworkBundle\Test\WebTestCase;

use Academic\Bundle\BugTrackingBundle\Entity\Repository\IssueRepository;
use Academic\Bundle\BugTrackingBundle\Entity\Issue;

/**
 * @dbIsolation
 */
class IssueRepositoryTest extends WebTestCase
{
    /** @var  IssueRepository */
    protected $repository;

    protected function setUp()
    {
        $this->initClient(['debug' => false], $this->generateBasicAuthHeader());
        $this->loadFixtures(['Academic\Bundle\BugTrackingBundle\Tests\Functional\DataFixtures\LoadIssueData']);
        $this->repository = $this->client
            ->getContainer()
            ->get('doctrine')
            ->getRepository(Issue::class);
    }

    public function testGetIssuesGroupedByStatus()
    {
        $result = reset($this->repository->getIssuesGroupedByStatus());

        $this->assertArrayHasKey('status', $result);
        $this->assertEquals($result['status'], 'open');
        $this->assertEquals(reset($result), 1);
    }
}
