<?php

namespace Academic\Bundle\BugTrackingBundle\Form\EventListener;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use Oro\Bundle\UserBundle\Entity\User;
use Oro\Bundle\EntityBundle\ORM\DoctrineHelper;

use Academic\Bundle\BugTrackingBundle\Entity\Issue;

class IssueSubscriber implements EventSubscriberInterface
{
    /**
     * Form factory.
     *
     * @var FormFactoryInterface
     */
    protected $factory;

    /**
     * @var DoctrineHelper
     */
    protected $doctrineHelper;

    /**
     * IssueSubscriber constructor.
     * @param FormFactoryInterface $factory
     * @param DoctrineHelper $doctrineHelper
     */
    public function __construct(FormFactoryInterface $factory, DoctrineHelper $doctrineHelper)
    {
        $this->factory = $factory;
        $this->doctrineHelper = $doctrineHelper;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            FormEvents::PRE_SET_DATA => 'preSetData',
        ];
    }

    /**
     * Removes or adds fields.
     *
     * @param FormEvent $event
     */
    public function preSetData(FormEvent $event)
    {
        /** @var Issue $issue */
        $issue = $event->getData();
        $form = $event->getForm();

        if (null === $issue) {
            return;
        }

        $form->add(
            'assignee',
            EntityType::class,
            [
                'class' => User::class,
                'choices' => $this->getUsers(),
                'choice_label' => 'username',
            ]
        )
            ->add(
                'reporter',
                EntityType::class,
                [
                    'class' => User::class,
                    'choices' => $this->getUsers(),
                    'choice_label' => 'username',
                ]
            );
    }

    /**
     * @return array
     */
    protected function getUsers()
    {
        $userRepo = $this->doctrineHelper->getEntityRepositoryForClass(User::class);

        return $userRepo->findBy(['enabled' => true]);
    }
}
