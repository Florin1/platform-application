<?php

namespace Academic\Bundle\BugTrackingBundle\Form\Handler;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Oro\Bundle\TagBundle\Entity\TagManager;
use Oro\Bundle\EntityBundle\ORM\DoctrineHelper;
use Oro\Bundle\TagBundle\Form\Handler\TagHandlerInterface;
use Academic\Bundle\BugTrackingBundle\Entity\Issue;

class IssueHandler implements TagHandlerInterface
{
    /**
     * @var FormInterface
     */
    protected $form;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var TagManager
     */
    protected $tagManager;

    /**
     * @var DoctrineHelper
     */
    protected $doctrineHelper;

    /**
     * IssueHandler constructor.
     * @param FormInterface $form
     * @param Request $request
     * @param DoctrineHelper $doctrineHelper
     */
    public function __construct(FormInterface $form, Request $request, DoctrineHelper $doctrineHelper)
    {
        $this->form = $form;
        $this->request = $request;
        $this->doctrineHelper = $doctrineHelper;
    }

    /**
     * Process form
     *
     * @param  Issue $entity
     * @return bool True on successful processing, false otherwise
     */
    public function process(Issue $entity)
    {
        $this->form->setData($entity);

        if (in_array($this->request->getMethod(), ['POST', 'PUT'])) {
            $this->form->submit($this->request);

            if ($this->form->isValid()) {
                $manager = $this->getManager(Issue::class);
                $manager->persist($entity);
                $manager->flush();

                return true;
            }
        }

        return false;
    }

    /**
     * @param TagManager $tagManager
     */
    public function setTagManager(TagManager $tagManager)
    {
        $this->tagManager = $tagManager;
    }

    /**
     * @param $class
     * @return \Doctrine\ORM\EntityManager|null
     */
    public function getManager($class)
    {
        return $this->doctrineHelper->getEntityManagerForClass($class);
    }
}
