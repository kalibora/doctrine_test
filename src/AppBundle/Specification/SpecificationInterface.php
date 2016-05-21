<?php

namespace AppBundle\Specification;

use Doctrine\ORM\EntityRepository;

interface SpecificationInterface
{
    public function isSatisfiedBy(CandidateInterface $object): bool;

    public function find(EntityRepository $repository): array;
}
