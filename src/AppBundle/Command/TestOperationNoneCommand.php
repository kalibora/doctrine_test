<?php

namespace AppBundle\Command;

use AppBundle\Entity\Customer;

class TestOperationNoneCommand extends AbstractTestOperationCommand
{
    protected function getOperationName()
    {
        return 'none';
    }

    protected function operation(Customer $customer)
    {
        return null;
    }
}
