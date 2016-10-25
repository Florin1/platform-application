<?php

namespace Academic\Bundle\BugTrackingBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use Academic\Bundle\BugTrackingBundle\Entity\Issue;
use Academic\Bundle\BugTrackingBundle\Form\EventListener\IssueSubscriber;

class IssueType extends AbstractType
{
    const NAME = 'academic_bug_tracking_issue';

    /**
     * @var string
     */
    protected $dataClass;

    /**
     * @var IssueSubscriber
     */
    private $issueSubscriber;

    /**
     * IssueType constructor.
     * @param $dataClass
     * @param IssueSubscriber $eventListener
     */
    public function __construct($dataClass, IssueSubscriber $eventListener)
    {
        $this->dataClass = $dataClass;
        $this->issueSubscriber = $eventListener;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'code',
                TextType::class,
                [
                    'label' => 'academic.bugtracking.issue.code.label',
                ]
            )
            ->add(
                'summary',
                TextType::class,
                [
                    'label' => 'academic.bugtracking.issue.summary.label',
                ]
            )
            ->add(
                'description',
                TextareaType::class,
                [
                    'label' => 'academic.bugtracking.issue.description.label',
                ]
            );
        if (!$builder->getData()->hasParent()) {
            $disabled = boolval($builder->getData()->getId()) && $builder->getData()->getChildren()->count() > 0;

            $builder->add(
                'type',
                'oro_enum_select',
                [
                    'label' => 'academic.bugtracking.issue.type.label',
                    'enum_code' => Issue::TYPE_ENUM_CODE,
                    'configs' => ['allowClear' => false],
                    'excluded_values' => [Issue::TYPE_SUBTASK],
                    'disabled' => $disabled
                ]
            );
        } else {
            $builder->add(
                'parent',
                EntityType::class, array(
                'class' => Issue::class,
                'disabled' => true,
            ));
        };
        $builder->add(
            'priority',
            'oro_enum_select',
            [
                'label' => 'academic.bugtracking.issue.priority.label',
                'enum_code' => Issue::PRIORITY_ENUM_CODE,
                'configs' => ['allowClear' => false]
            ]
        )
            ->add(
                'resolution',
                'oro_enum_select',
                [
                    'label' => 'academic.bugtracking.issue.resolution.label',
                    'enum_code' => Issue::RESOLUTION_ENUM_CODE,
                    'configs' => ['allowClear' => false]
                ]
            );
        $builder->addEventSubscriber($this->issueSubscriber);
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => $this->dataClass,
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return self::NAME;
    }
}
