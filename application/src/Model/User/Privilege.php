<?php
namespace Main\Model\User;

use E4u\Model\Base;
use Main\Model\User;

/**
 * @Entity(readOnly=true)
 * @Table(name="users_privileges")
 */
class Privilege extends Base
{
    const
        ADMIN = 255,
        EDIT_GAMES = 10;

    /**
     * @var User
     * @Id @ManyToOne(targetEntity="Main\Model\User", inversedBy="privileges")
     */
    protected $user;

    /** @Id @Column(type="integer") */
    protected $value;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->value;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return int
     */
    public function getValue()
    {
        return $this->value;
    }
}
