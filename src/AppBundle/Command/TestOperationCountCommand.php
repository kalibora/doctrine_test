<?php

namespace AppBundle\Command;

use AppBundle\Entity\Customer;

class TestOperationCountCommand extends AbstractTestOperationCommand
{
    protected function getOperationName()
    {
        return 'count';
    }

    protected function operation(Customer $customer)
    {
        return $customer->getOrders()->count();
    }
}
