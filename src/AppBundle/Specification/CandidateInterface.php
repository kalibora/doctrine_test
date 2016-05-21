<?php

namespace AppBundle\Specification;

interface CandidateInterface
{
    public function isSatisfiedBy(SpecificationInterface $spec): bool;
}
