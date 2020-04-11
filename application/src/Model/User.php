<?php
namespace Main\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityRepository;
use E4u\Authentication\Identity\User as E4uUser;

/**
 * @Entity(repositoryClass="Main\Model\User\Repository")
 * @Table(name="users")
 */
class User extends E4uUser
{
    /** @Column(type="string", nullable=true) */
    protected $email;

    /** @Column(type="string") */
    protected $name;

    /** @Column(type="string") */
    protected $locale = 'pl';

    /** @Column(type="boolean") */
    protected $active = false;

    /**
     * @var User\Profile[]|ArrayCollection
     * @OneToMany(targetEntity="Main\Model\User\Profile", mappedBy="user", cascade={"all"}, orphanRemoval=true)
     **/
    protected $profiles;

    /**
     * @var User\Privilege[]|ArrayCollection
     * @OneToMany(targetEntity="Main\Model\User\Privilege", mappedBy="user", cascade={"all"}, orphanRemoval=true, indexBy="value")
     */
    protected $privileges;

    /**
     * @return string|null
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * Implements Identity
     * @param  int|bool $value
     * @return bool
     */
    public function hasPrivilege($value)
    {
        if (true === $value) {
            return true;
        }

        $value = (int)$value;
        foreach ($this->privileges as $privilege) {
            if ($privilege->getValue() === $value) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return User\Profile[]
     */
    public function getProfiles()
    {
        return $this->profiles;
    }

    /**
     * Implements Identity
     *
     * @param  string $cookie
     * @return User
     */
    public static function findByCookie($cookie)
    {
        $id = strtok($cookie, '/');
        $value = strtok('');

        $token = User\Token::findOneByUserTypeAndValue((int)$id, User\Token::AUTO_LOGIN, $value);
        if (null === $token) {
            return null;
        }

        $user = $token->getUser();
        $token->destroy();
        return $user;
    }

    /**
     * Implements Identity
     * @return string
     */
    public function getCookie()
    {
        $token = User\Token::create([
            'user' => $this,
            'type' => 'autologin',
            'expires_at' => (new \DateTime())->modify('+1 year'),
        ]);

        return $this->id() . '/' . $token->getValue();
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param  User\Profile $profile
     * @param  bool $keepConsistency
     * @return $this
     */
    public function delFromProfiles(User\Profile $profile, $keepConsistency = true)
    {
        $this->_delFrom('profiles', $profile, $keepConsistency);
        return $this;
    }

    /**
     * @param  User\Profile $profile
     * @return $this
     */
    public function addToProfiles($profile)
    {
        $this->_addTo('profiles', $profile, true);
        return $this;
    }

    /**
     * @return bool
     */
    public function hasPassword()
    {
        return !empty($this->encrypted_password);
    }

    /**
     * @return User\Repository|EntityRepository
     */
    public static function getRepository()
    {
        return parent::getRepository();
    }
}