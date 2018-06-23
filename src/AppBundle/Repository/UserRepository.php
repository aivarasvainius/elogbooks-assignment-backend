<?php

namespace AppBundle\Repository;

use AppBundle\Form\FilterType\Model\ListFilter;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

class UserRepository extends EntityRepository
{
    /**
     * @param ListFilter $listFilterModel
     *
     * @return QueryBuilder
     */
    public function filterAndReturnQuery(ListFilter $listFilterModel)
    {
        $qb = $this->createQueryBuilder('q')
            ->setMaxResults(ListFilter::LIMIT)
        ;

        $this->applyFilter($qb, $listFilterModel);

        return $qb->getQuery();
    }

    /**
     * @param QueryBuilder $qb
     * @param ListFilter   $listFilterModel
     *
     * @return $this
     */
    public function applyFilter(QueryBuilder $qb, ListFilter $listFilterModel)
    {
        if ($listFilterModel->getOrderKey()) {
            $qb->orderBy(
                sprintf('q.%s', $listFilterModel->getOrderKey()),
                $listFilterModel->getOrderDirection()
            );
        }

        if ($listFilterModel->getKeyword()) {
            $qb
                ->andWhere('LOWER(q.username) LIKE :keyword')
                ->setParameter(':keyword', '%'.addcslashes($listFilterModel->getKeyword(), '%_').'%')
            ;
        }

    }
}