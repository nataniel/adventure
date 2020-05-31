<?php
namespace Main\Model;

use E4u\Model\Entity;

/**
 * @Entity
 * @Table(name="games")
 */
class Game extends Entity
{
    /** @Column(type="string", unique=true) */
    protected $name;

    /** @Column(type="string") */
    protected $description;

    /** @Column(type="datetime", nullable=true) */
    protected $created_at;

    /**
     * @var User
     * @ManyToOne(targetEntity="Main\Model\User", inversedBy="created_games")
     * @JoinColumn(name="created_by", referencedColumnName="id")
     */
    protected $created_by;

    /**
     * @var Game\Operator[]
     * @OneToMany(targetEntity="Main\Model\Game\Operator", mappedBy="game", cascade={"all"})
     **/
    protected $operators;

    /**
     * @var Page[]
     * @OneToMany(targetEntity="Main\Model\Page", mappedBy="game", cascade={"all"})
     **/
    protected $pages;

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return Game\Operator[]
     */
    public function getOperators()
    {
        return $this->operators;
    }

    /**
     * @return Page[]
     */
    public function getPages()
    {
        return $this->pages;
    }

    /**
     * @param  User $user
     * @return Game\Operator|null
     */
    public function getOperatorFor($user)
    {
        foreach ($this->operators as $operator) {
            if ($user === $operator->getUser()) {
                return $operator;
            }
        }

        return null;
    }

    /**
     * @param  mixed $operator
     * @param  bool $keepConsistency
     * @return $this
     */
    public function addToOperators($operator, $keepConsistency = true)
    {
        $this->_addTo('operators', $operator, $keepConsistency);
        return $this;
    }

    /**
     * @return User
     */
    public function getCreatedBy()
    {
        return $this->created_by;
    }

    /**
     * @param  User|mixed $user
     * @param  bool $keepConsistency
     * @return $this
     */
    public function setCreatedBy($user, $keepConsistency = true)
    {
        $this->_set('created_by', $user, $keepConsistency);
        return $this;
    }
}