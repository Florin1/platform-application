<?php

namespace Academic\Bundle\BugTrackingBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
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
     */
    public function indexAction()
    {
        return [
            'entity_class' => $this->container->getParameter('academic.bug_tracking.form.type.issue.class')
        ];
    }

    /**
     * @Route("/create", name="academic_bug_tracking_create")
     * @Template("AcademicBugTrackingBundle:Issue:update.html.twig")
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
        return [
            'entity' => $issue
        ];
    }

    /**
     * @param Issue $issue
     * @return array|RedirectResponse
     */
    public function update(Issue $issue)
    {
        return $this->get('oro_form.model.update_handler')->update(
            $issue,
            $this->createForm(IssueType::NAME),
            $this->get('translator')->trans('academic.bugtracking.issue.saved_message')
        );
    }
}
