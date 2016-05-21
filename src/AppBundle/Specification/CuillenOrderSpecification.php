<?php

namespace AppBundle\Specification;

use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityRepository;

class CuillenOrderSpecification implements SpecificationInterface
{
    public function isSatisfiedBy(CandidateInterface $order): bool
    {
        $discountableAmount = 0;

        $taliskerCriteria = Criteria::create()
            ->where(
                Criteria::expr()->eq('product', 'Talisker')
            )
        ;

        foreach ($order->getLineItems()->matching($taliskerCriteria) as $lineItem) {
            $discountableAmount += $lineItem->getCost();
        }

        return $discountableAmount >= 5000;
    }

    public function find(EntityRepository $orderRepository): array
    {
        return $orderRepository
            ->createQueryBuilder('o')
            ->addSelect('SUM(l.cost) AS HIDDEN total')
            ->join('o.lineItems', 'l')
            ->where('l.product = :product')
            ->groupBy('o')
            ->having('total >= :total')
            ->setParameter('product', 'Talisker')
            ->setParameter('total', 5000)
            ->getQuery()
            ->getResult()
        ;
        //return $this->matching($spec->toCriteria());
    }
}
