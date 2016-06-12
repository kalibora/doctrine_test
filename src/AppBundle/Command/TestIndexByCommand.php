<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class TestIndexByCommand extends ContainerAwareCommand
{
    use UtilTrait;

    protected function configure()
    {
        $this
            ->setName('test:index-by')
            ->setDescription('')
            ->addOption(
                'index-by',
                null,
                InputOption::VALUE_NONE
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $doctrine = $this->getDoctrine();
        $orderRepository = $doctrine->getRepository('AppBundle:Order');

        $indexBy = null;
        if ($input->getOption('index-by')) {
            $indexBy = 'o.id';
        }

        $orders = $orderRepository->createQueryBuilder('o', $indexBy)
            ->orderBy('o.id')
            ->setMaxResults(5)
            ->getQuery()
            ->getResult()
        ;

        dump([
            'order_keys' => array_keys($orders),
        ]);
    }
}
