<?php
namespace Main\Model;

use Doctrine\ORM\PersistentCollection;
use E4u\Model\Entity;

/**
 * @Entity
 * @Table(name="games")
 */
class Game extends Entity
{
    /** @Column(type="string", unique=true) */
    protected $name = '';

    /** @Column(type="string") */
    protected $description = '';

    /** @Column(type="boolean") */
    protected $public = false;

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

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return Game\Operator[]|PersistentCollection
     */
    public function getOperators(): PersistentCollection
    {
        return $this->operators;
    }

    /**
     * @return Page[]|PersistentCollection
     */
    public function getPages(): PersistentCollection
    {
        return $this->pages;
    }

    public function getOperatorFor(User $user): ?Game\Operator
    {
        foreach ($this->operators as $operator) {
            if ($user === $operator->getUser()) {
                return $operator;
            }
        }

        return null;
    }

    public function addToOperators($operator, bool $keepConsistency = true): self
    {
        $this->_addTo('operators', $operator, $keepConsistency);
        return $this;
    }

    public function getCreatedBy(): User
    {
        return $this->created_by;
    }

    public function setCreatedBy($user, bool $keepConsistency = true): self
    {
        $this->_set('created_by', $user, $keepConsistency);
        return $this;
    }

    public function isPublic(): bool
    {
        return $this->public;
    }

    public function setPublic(bool $public = true): Game
    {
        $this->public = $public;
        return $this;
    }

    public function isAvailableFor(?User $user): bool
    {
        if ($this->public) {
            return true;
        }

        if (!$user) {
            return false;
        }

        $operator = $this->getOperatorFor($user);
        return !empty($operator);
    }
}