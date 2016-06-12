<?php

namespace AppBundle\Command;

use Doctrine\DBAL\Logging\DebugStack;

trait UtilTrait
{
    private $stack;

    protected function getDoctrine()
    {
        $doctrine = $this->getContainer()->get('doctrine');
        $this->stack = new DebugStack();
        $doctrine
            ->getConnection()
            ->getConfiguration()
            ->setSQLLogger($this->stack)
        ;

        return $doctrine;
    }

    protected function getExecutedSqls($max = 5)
    {
        $sqls = array_slice(
            array_map(function ($query) {
                return $query['sql'];
            }, $this->stack->queries),
            0,
            $max
        );

        return $sqls;
    }

    protected function countExecutedQueries()
    {
        return count($this->stack->queries);
    }
}
