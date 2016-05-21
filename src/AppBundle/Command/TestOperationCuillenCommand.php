<?php

namespace AppBundle\Command;

use AppBundle\Entity\Customer;

class TestOperationCuillenCommand extends AbstractTestOperationCommand
{
    protected function getOperationName()
    {
        return 'cuillen';
    }

    protected function operation(Customer $customer)
    {
        return $customer->getCuillenMonths();
    }
}
