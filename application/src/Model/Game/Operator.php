<?php
namespace Main\Model\Game;

use E4u\Model\Entity;
use Main\Model\Game;
use Main\Model\User;

/**
 * @Entity
 * @Table(name="games_operators", uniqueConstraints={
 *     @UniqueConstraint(name="game_user", columns={"game_id", "user_id"})
 * })
 */
class Operator extends Entity
{
    const TYPE_OWNER = 'owner',
        TYPE_CONTENT = 'content';

    /**
     * @var Game
     * @ManyToOne(targetEntity="Main\Model\Game", inversedBy="operators")
     */
    protected $game;

    /**
     * @var User
     * @ManyToOne(targetEntity="Main\Model\User", inversedBy="operators")
     */
    protected $user;

    /** @Column(type="string") */
    protected $type;

    /**
     * @return Game
     */
    public function getGame()
    {
        return $this->game;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param  string $type
     * @return bool
     */
    public function isType($type)
    {
        return $type === $this->type;
    }
}