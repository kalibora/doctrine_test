<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use AppBundle\Entity\Customer;

class TestCuillenOrderCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('test:cuillen-order')
            ->setDescription('test cuillen-order ')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $doctrine = $this->getContainer()->get('doctrine');
        $orderRepository = $doctrine->getRepository('AppBundle:Order');
        $orders = $orderRepository->findAll();

        $spec = new \AppBundle\Specification\CuillenOrderSpecification();
        $cuillenOrders = $orderRepository->findBySpecification($spec);

        $output->writeln([
            'all' => count($orders),
            'cuillen' => count($cuillenOrders),
        ]);
    }
}
