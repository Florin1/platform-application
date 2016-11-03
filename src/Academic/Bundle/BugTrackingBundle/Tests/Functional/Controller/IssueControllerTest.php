<?php

namespace Academic\Bundle\BugTrackingBundle\Tests\Functional\Controller;

use Oro\Bundle\TestFrameworkBundle\Test\WebTestCase;

/**
 * @dbIsolation
 */
class IssueControllerTest extends WebTestCase
{
    protected $issue;

    protected function setUp()
    {
        $this->initClient(['debug' => false], $this->generateBasicAuthHeader());
        $this->loadFixtures(['Academic\Bundle\BugTrackingBundle\Tests\Functional\DataFixtures\LoadIssueData']);
        $this->issue = $this->getReference('issue');
    }

    public function testIndexAction()
    {
        $crawler = $this->client->request('GET', $this->getUrl('academic_bug_tracking_index'));
        $result = $this->client->getResponse();
        $this->assertHtmlResponseStatusCodeEquals($result, 200);
        $this->assertContains('bug-tracking-issues-grid', $crawler->html());
    }

    public function testCreateAction()
    {
        $crawler = $this->client->request('GET', $this->getUrl('academic_bug_tracking_create'));

        $form = $crawler->selectButton('Save and Close')->form();
        $form['academic_bug_tracking_issue[code]'] = 'New task';
        $form['academic_bug_tracking_issue[description]'] = 'New description';
        $form['academic_bug_tracking_issue[summary]'] = '2014-03-04T20:00:00+0000';

        $this->client->followRedirects(true);
        $crawler = $this->client->submit($form);
        $result = $this->client->getResponse();
        $this->assertHtmlResponseStatusCodeEquals($result, 200);
        $this->assertContains("Successfully saved issue", $crawler->html());
    }

    public function testUpdateAction()
    {
        $crawler = $this->client->request(
            'GET',
            $this->getUrl('academic_bug_tracking_update', ['id' => $this->issue->getId()])
        );

        $form = $crawler->selectButton('Save and Close')->form();
        $form['academic_bug_tracking_issue[description]'] = 'Issue description updated';

        $this->client->followRedirects(true);
        $crawler = $this->client->submit($form);
        $result = $this->client->getResponse();

        $this->assertHtmlResponseStatusCodeEquals($result, 200);
        $this->assertContains("Successfully saved issue", $crawler->html());
    }

    public function testViewAction()
    {
        $this->client->request(
            'GET',
            $this->getUrl('academic_bug_tracking_view', ['id' => $this->issue->getId()])
        );
        $result = $this->client->getResponse();

        $this->assertHtmlResponseStatusCodeEquals($result, 200);
        $this->assertContains('Issue description updated', $result->getContent());
    }

    public function testChartAction()
    {
        $crawler = $this->client->request(
            'GET',
            $this->getUrl('academic_bug_tracking_dashboard_chart', ['widget' => 'issues_chart'])
        );
        $result = $this->client->getResponse();
        $this->assertHtmlResponseStatusCodeEquals($result, 200);
        $this->assertContains('Issue status chart', $crawler->html());
    }
}
