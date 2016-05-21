<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Customer;
use AppBundle\Entity\Order;
use AppBundle\Entity\LineItem;

class LoadCustomerData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $customer = (new Customer())
            ->setName('Target Customer')
        ;
        $manager->persist($customer);

        $begin = new \DateTimeImmutable('2014-01-01');
        $end = new \DateTimeImmutable('2017-01-01');
        $interval = new \DateInterval('P1D');
        $daterange = new \DatePeriod($begin, $interval, $end);

        foreach ($daterange as $date) {
            $order = (new Order())
                ->setDate($date)
                ->setCustomer($customer)
            ;
            $manager->persist($order);

            if ($date->format('Y-m-d') === '2014-04-01') {
                $this->orderTalisker($manager, $order, 5);
            } elseif ($date->format('Y-m-d') === '2016-07-31') {
                $this->orderTalisker($manager, $order, 10);
            } else {
                $this->orderTalisker($manager, $order, 1);
            }

            $this->orderSake($manager, $order, 3);
        }

        $manager->flush();
    }

    public function orderTalisker(ObjectManager $manager, Order $order, $quantity)
    {
        foreach (range(1, $quantity) as $i) {
            $talisker = (new LineItem())
                ->setProduct('Talisker')
                ->setCost(1000)
                ->setOrder($order)
            ;

            $manager->persist($talisker);
        }
    }

    public function orderSake(ObjectManager $manager, Order $order, $quantity)
    {
        foreach (range(1, $quantity) as $i) {
            $sake = (new LineItem())
                ->setProduct('Sake')
                ->setCost(2000)
                ->setOrder($order)
            ;

            $manager->persist($sake);
        }
    }
}
