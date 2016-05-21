<?php

namespace AppBundle\Specification;

trait CandidateTrait
{
    public function isSatisfiedBy(SpecificationInterface $spec): bool
    {
        return $spec->isSatisfiedBy($this);
    }
}
