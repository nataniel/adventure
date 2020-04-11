<?php
namespace Main\Model\User\Profile;

use E4u\Model\Repository as E4uRepository;
use Main\Model\User\Profile;

class Repository extends E4uRepository
{
    /**
     * @param  string $className
     * @param  string $profile_id
     * @return Profile|null
     */
    public function findOneByTypeAndProfileId($className, $profile_id)
    {
        $qb = $this->_em->createQueryBuilder();
        $ex = $this->_em->getExpressionBuilder();

        $qb->select('p')
            ->from(Profile::class, 'p')
            ->where($ex->isInstanceOf('p', $className))
            ->andWhere($ex->eq('p.profile_id', $ex->literal($profile_id)));
        return $qb->getQuery()->getOneOrNullResult();
    }
}
