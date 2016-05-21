<?php

namespace AppBundle\Entity;

use AppBundle\Specification\SpecificationInterface;

class SpecificationRepository extends \Doctrine\ORM\EntityRepository
{
    public function findBySpecification(SpecificationInterface $spec): array
    {
        return $spec->find($this);
    }
}
