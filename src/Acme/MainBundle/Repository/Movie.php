<?php

namespace Acme\MainBundle\Repository;

use Doctrine\ORM\EntityRepository;

class Movie extends EntityRepository
{
    public function filterQuery(array $filter)
    {
        $q = $this->createQueryBuilder('m');
        $q->leftJoin('m.categories', 'mc');

        if ($filter['title']) {
            $q->andWhere('m.title LIKE :title');
            $q->setParameter('title', '%' . $filter['title'] . '%');
        }

        if ($filter['translation'] !== '') {
            $translate = array(
                0 => 'Lektor',
                1 => 'Czytany'
            );

            $q->andWhere('m.translation LIKE :translation');
            $q->setParameter('translation', $translate[$filter['translation']]);
        }

        if (isset($filter['categories']) && count($filter['categories'])) {
            $q->andWhere('mc.id IN (:categories)');
            $q->setParameter('categories', $filter['categories']);
        }

        return $q->getQuery()->execute();
    }
}
