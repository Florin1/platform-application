<?php

namespace Academic\Bundle\BugTrackingBundle\Form\EventListener;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use Doctrine\ORM\EntityRepository;

use Oro\Bundle\UserBundle\Entity\User;

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
     * IssueSubscriber constructor.
     * @param FormFactoryInterface $factory
     */
    public function __construct(FormFactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            FormEvents::PRE_SET_DATA => 'preSetData',
        );
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

        $form->add('assignee', EntityType::class, array(
                'class' => User::class,
                'query_builder' => $this->getUsersClosure(),
                'choice_label' => 'username',
            )
        )
            ->add('reporter', EntityType::class, array(
                    'class' => User::class,
                    'query_builder' => $this->getUsersClosure(),
                    'choice_label' => 'username',
                )
            );
    }

    /**
     * @return callable
     */
    protected function getUsersClosure()
    {
        return function (EntityRepository $entityRepository) {
            return $entityRepository->createQueryBuilder('user')
                ->where('user.enabled = :enabled')
                ->setParameter('enabled', true);
        };
    }
}
