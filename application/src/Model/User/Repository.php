<?php
namespace Main\Model\User;

use E4u\Model\Repository as E4uRepository,
    E4u\Authentication\Identity\Repository as UserRepository;
use Main\Model\User;

class Repository extends E4uRepository implements UserRepository
{
    /**
     * @param  string $login
     * @return User|null
     */
    public function findOneByLogin($login)
    {
        if (empty($login)) {
            return null;
        }

        $dql = "SELECT u FROM Main\\Model\\User u
             WHERE u.email = :login OR u.login = :login";
        $result = $this->_em->createQuery($dql)
            ->setParameter('login', $login)
            ->setMaxResults(1)
            ->getOneOrNullResult();
        return $result;
    }
}