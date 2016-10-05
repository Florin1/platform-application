<?php

namespace Academic\Bundle\BugTrackingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\Config;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\ConfigField;
use Academic\Bundle\BugTrackingBundle\Model\ExtendIssue;
use Oro\Bundle\UserBundle\Entity\User;

/**
 * Issue
 *
 * @ORM\Table(name="oro_academic_issue")
 * @ORM\Entity(repositoryClass="Academic\Bundle\BugTrackingBundle\Entity\Repository\IssueRepository")
 * @ORM\HasLifecycleCallbacks()
 * @Config(
 *   defaultValues={
 *       "tag"={
 *          "enabled"=true
 *       }
 *    }
 * )
 */
class Issue extends ExtendIssue
{
    const RESOLUTION_ENUM_CODE = 'oac_issue_resolution';
    const TYPE_ENUM_CODE = 'oac_issue_type';
    const PRIORITY_ENUM_CODE = 'oac_issue_priority';

    const TYPE_TASK = 'task';
    const TYPE_SUBTASK = 'subtask';
    const TYPE_BUG = 'bug';

    const PRIORITY_BLOCKER = 'blocker';
    const PRIORITY_CRITICAL = 'critical';
    const PRIORITY_MAJOR = 'major';
    const PRIORITY_TRIVIAL = 'trivial';

    const RESOLUTION_FIXED = 'fixed';
    const RESOLUTION_DUPLICATE = 'duplicate';
    const RESOLUTION_INCOMPLETE = 'incomplete';
    const RESOLUTION_CANNOT_REPRODUCE = 'cannot_reproduce';
    const RESOLUTION_DONE = 'done';

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="summary", type="string", length=255)
     */
    protected $summary;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=255)
     */
    protected $code;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    protected $description;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdAt", type="datetime")
     */
    protected $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updatedAt", type="datetime")
     */
    protected $updatedAt;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="Oro\Bundle\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    protected $assignee;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="Oro\Bundle\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    protected $reporter;

    /**
     * @ORM\ManyToMany(targetEntity="Oro\Bundle\UserBundle\Entity\User")
     * @ORM\JoinTable(name="oro_academic_issue_user",
     *   joinColumns={@ORM\JoinColumn(name="issue_id", referencedColumnName="id")},
     *   inverseJoinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")}
     *   )
     */
    protected $collaborators;

    /**
     * @ORM\OneToMany(targetEntity="Academic\Bundle\BugTrackingBundle\Entity\Issue", mappedBy="parent")
     */
    protected $children;

    /**
     * @ORM\ManyToOne(targetEntity="Academic\Bundle\BugTrackingBundle\Entity\Issue", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     */
    protected $parent;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->collaborators = new \Doctrine\Common\Collections\ArrayCollection();
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set summary
     *
     * @param string $summary
     * @return Issue
     */
    public function setSummary($summary)
    {
        $this->summary = $summary;

        return $this;
    }

    /**
     * Get summary
     *
     * @return string
     */
    public function getSummary()
    {
        return $this->summary;
    }

    /**
     * Set code
     *
     * @param string $code
     * @return Issue
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Issue
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Issue
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return Issue
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set assignee
     *
     * @param null|User $assignee
     * @return Issue
     */
    public function setAssignee(User $assignee = null)
    {
        $this->assignee = $assignee;

        return $this;
    }

    /**
     * Get assignee
     * @return null|User
     */
    public function getAssignee()
    {
        return $this->assignee;
    }

    /**
     * Set reporter
     *
     * @param User $reporter
     * @return Issue
     */
    public function setReporter(User $reporter = null)
    {
        $this->reporter = $reporter;

        return $this;
    }

    /**
     * Get reporter
     *
     * @return null|User
     */
    public function getReporter()
    {
        return $this->reporter;
    }

    /**
     * Add collaborator
     *
     * @param User $collaborator
     * @return Issue
     */
    public function addCollaborator(User $collaborator)
    {
        $this->collaborators[] = $collaborator;

        return $this;
    }

    /**
     * Remove collaborator
     *
     * @param User $collaborator
     */
    public function removeCollaborator(User $collaborator)
    {
        $this->collaborators->removeElement($collaborator);
    }

    /**
     * Get collaborators
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCollaborators()
    {
        return $this->collaborators;
    }

    /**
     * Add child
     *
     * @param \Academic\Bundle\BugTrackingBundle\Entity\Issue $child
     * @return Issue
     */
    public function addChild(Issue $child)
    {
        $this->children[] = $child;

        return $this;
    }

    /**
     * Remove child
     *
     * @param \Academic\Bundle\BugTrackingBundle\Entity\Issue $child
     */
    public function removeChild(Issue $child)
    {
        $this->children->removeElement($child);
    }

    /**
     * Get children
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Set parent
     *
     * @param \Academic\Bundle\BugTrackingBundle\Entity\Issue $parent
     * @return Issue
     */
    public function setParent(Issue $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \Academic\Bundle\BugTrackingBundle\Entity\Issue
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set prePersistValues
     *
     * @ORM\PrePersist
     */
    public function setPrePersistValues()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

    /**
     * Set preUpdateValues
     *
     * @ORM\PreUpdate
     */
    public function setPreUpdateValues()
    {
        $this->updatedAt = new \DateTime();
    }
}
