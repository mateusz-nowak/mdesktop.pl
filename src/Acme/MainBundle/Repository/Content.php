<?php

namespace Acme\MainBundle\Repository;

use Doctrine\ORM\EntityRepository;

class Content extends EntityRepository
{
    /**
     * Slug for news
     *
     * @var string self::NEWS
     */
    const NEWS = 'wiadomosci';

    /**
     * Slug for pages
     *
     * @var string self::PAGE
     */
    const PAGE = 'podstrony-dynamiczne-cms';

    public function findAllByCategoryName($categoryName)
    {
        $queryBuilder = $this
            ->createQueryBuilder('c')
            ->leftJoin('c.categories', 'cc')
            ->where('cc.type = :type')
            ->andWhere('cc.slug = :slug')
            ->setParameters(array(
                'type' => 1,
                'slug' => $categoryName,
            ));

        return $queryBuilder->getQuery()->getResult();
    }
}
