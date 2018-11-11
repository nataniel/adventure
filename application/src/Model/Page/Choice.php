<?php
namespace Main\Model\Page;

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

    /**
     * @var Page
     * @ManyToOne(targetEntity="Main\Model\Page", inversedBy="sources")
     */
    protected $target;

    /** @Column(type="string") */
    protected $name;

    /** @Column(type="string") */
    protected $description = '';

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
     * @return Page
     */
    public function getTarget()
    {
        return $this->target;
    }

    /**
     * @param  Page $target
     * @param  bool $keepConsistency
     * @return $this
     */
    public function setTarget($target, $keepConsistency = true)
    {
        $this->_set('target', $target, $keepConsistency);
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param  string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
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
     * @param  string $description
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }
}