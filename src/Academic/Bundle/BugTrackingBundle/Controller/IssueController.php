<?php

namespace Academic\Bundle\BugTrackingBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;

use Academic\Bundle\BugTrackingBundle\Form\Handler\IssueHandler;
use Academic\Bundle\BugTrackingBundle\Entity\Issue;
use Academic\Bundle\BugTrackingBundle\Form\Type\IssueType;

/**
 * @Route("issue")
 */
class IssueController extends Controller
{
    /**
     * @Route(
     *      name="academic_bug_tracking_index",
     * )
     * @Template
     * @return array
     */
    public function indexAction()
    {
        return [
            'entity_class' => $this->container->getParameter('academic.bug_tracking.issue.class')
        ];
    }

    /**
     * @Route("/create", name="academic_bug_tracking_create")
     * @Template("AcademicBugTrackingBundle:Issue:update.html.twig")
     * @return array|RedirectResponse
     */
    public function createAction()
    {
        return $this->update(new Issue());
    }

    /**
     * @Route("/update/{id}", name="academic_bug_tracking_update", requirements={"id"="\d+"})
     * @Template()
     * @param Issue $issue
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function updateAction(Issue $issue)
    {
        return $this->update($issue);
    }

    /**
     * @Route("/view/{id}", name="academic_bug_tracking_view", requirements={"id"="\d+"})
     * @Template()
     * @param Issue $issue
     * @return array
     */
    public function viewAction(Issue $issue)
    {
        return ['entity' => $issue];
    }

    /**
     * @param Issue $issue
     * @return array|RedirectResponse
     */
    public function update(Issue $issue)
    {
        /** @var  IssueHandler $issueHandler */
        $issueHandler = $this->container->get('academic.bug_tracking.form.handler.issue');
        $issue = $issueHandler->updateIssue($issue);

        return $this->get('oro_form.model.update_handler')->update(
            $issue,
            $this->createForm(IssueType::NAME, $issue),
            $this->get('translator')->trans('academic.bugtracking.issue.saved_message')
        );
    }

    /**
     * @Route(
     *      "/chart/{widget}",
     *      name="academic_bug_tracking_dashboard_chart",
     *      requirements={"widget"="[\w-]+"}
     * )
     * @Template("AcademicBugTrackingBundle:Dashboard:issues_chart_widget.html.twig")
     * @param $widget
     * @return array
     */
    public function chartAction($widget)
    {
        $chartProvider = $this->get('academic.bug_tracking.provider.chart.issue');
        $items = $chartProvider->getIssueChartData();

        $viewBuilder = $this->container->get('oro_chart.view_builder');
        $view = $viewBuilder
            ->setArrayData($items)
            ->setOptions([
                'name' => 'bar_chart',
                'data_schema' => [
                    'label' => [
                        'field_name' => 'status',
                        'label' => 'oro.dashboard.issues_chart.chart.label',
                        'type' => 'string'
                    ],
                    'value' => [
                        'field_name' => 'count',
                        'label' => 'oro.dashboard.issues_chart.chart.value',
                        'type' => 'number'
                    ]
                ],
            ])
            ->setDataMapping(array('label' => 'status', 'value' => 'count'))
            ->getView();

        $widgetAttr = $this->get('oro_dashboard.widget_configs')->getWidgetAttributesForTwig($widget);
        $widgetAttr['chartView'] = $view;

        return $widgetAttr;
    }
}
