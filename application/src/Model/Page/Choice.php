<?php
namespace Main\Model\Page;

use E4u\Common\StringTools;
use E4u\Model\Entity;
use Main\Model\Page;

/**
 * @Entity
 * @Table(name="pages_choices")
 */
class Choice extends Entity
{
    /**
     * @var Page
     * @ManyToOne(targetEntity="Main\Model\Page", inversedBy="choices")
     */
    protected $parent;

    /** @Column(type="string") */
    protected $target;

    /** @Column(type="string") */
    protected $description;

    /** @Column(type="string") */
    protected $status = '';

    /** @Column(type="integer", nullable=true) */
    protected $position;

    /**
     * @return Page
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param  Page $parent
     * @param  bool $keepConsistency
     * @return $this
     */
    public function setParent($parent, $keepConsistency = true)
    {
        $this->_set('parent', $parent, $keepConsistency);
        return $this;
    }

    /**
     * @return string
     */
    public function getTarget()
    {
        return $this->target;
    }

    /**
     * @return Page|null
     */
    public function findTargetPage()
    {
        return Page::findOneBy([ 'name' => $this->target ]);
    }

    /**
     * @param  string $target
     * @return $this
     */
    public function setTarget($target)
    {
        $this->target = StringTools::toUrl(trim($target, '#'), true);
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param  string $description
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return string
     */
    public function toString()
    {
        return sprintf('#%s: %s', $this->target, $this->description);
    }
}