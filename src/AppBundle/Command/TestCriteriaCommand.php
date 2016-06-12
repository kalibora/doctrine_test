<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class TestCriteriaCommand extends ContainerAwareCommand
{
    use UtilTrait;

    protected function configure()
    {
        $this
            ->setName('test:criteria')
            ->setDescription('')
            ->addOption(
                'with-criteria',
                null,
                InputOption::VALUE_NONE
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $doctrine = $this->getDoctrine();
        $customerRepository = $doctrine->getRepository('AppBundle:Customer');

        $customer = $customerRepository->findOneByName('Target Customer');
        $date = new \DateTimeImmutable('2016-01-01');
        $stopwatch = $this->getStopwatch();

        $stopwatch->start('find');
        if ($input->getOption('with-criteria')) {
            $orders = $customer->getOrdersByDateWithCriteria($date);
        } else {
            $orders = $customer->getOrdersByDate($date);
        }
        $findEvent = $stopwatch->stop('find');

        foreach ($orders as $order) {
            echo $order->getId(), ': ', $order->getDate()->format('Y-m-d'), PHP_EOL;
        }

        dump([
            'sqls'     => $this->getExecutedSqls(),
            'duration' => $findEvent->getDuration(),
            'memory'   => $findEvent->getMemory() / 1024 / 1024,
        ]);
    }
}
