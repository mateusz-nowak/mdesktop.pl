<?php

namespace Acme\MainBundle\Repository;

use Doctrine\ORM\EntityRepository;

class Shoutbox extends EntityRepository
{
    public function getLastShouts($maxResults = 10)
    {
        $queryBuilder = $this
            ->createQueryBuilder('s')
            ->leftJoin('s.user', 'u')
            ->setMaxResults($maxResults)
            ->orderBy('s.id', 'DESC');

        return $queryBuilder->getQuery()->getResult();
    }
}
