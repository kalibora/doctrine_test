<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableCell;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;
use AppBundle\Entity\Customer;

abstract class AbstractTestOperationCommand extends ContainerAwareCommand
{
    use UtilTrait;

    protected static $modeFindMethodMap = [
        'join'       => 'findJoinedCustomerByName',
        'eager'      => 'findEagerCustomerByName',
        'lazy'       => 'findLazyCustomerByName',
        'extra_lazy' => 'findExtraLazyCustomerByName',
    ];

    abstract protected function getOperationName();
    abstract protected function operation(Customer $customer);

    protected function configure()
    {
        $this
            ->setName('test:operation:' . $this->getOperationName())
            ->setDescription('test for ' . $this->getOperationName())
            ->addOption(
                'mode',
                null,
                InputOption::VALUE_REQUIRED
            )
        ;
    }

    protected function executeAllMode(InputInterface $input, OutputInterface $output)
    {
        $rows = [
            'header'      => [ucfirst($this->getOperationName())],
            'query_count' => ['Query count'],
            'duration'    => ['Duration [msec]'],
            'memory'      => ['Memory [MB]'],
        ];
        $sqls = [];

        foreach (array_keys(static::$modeFindMethodMap) as $mode) {
            $process = new Process("{$_SERVER['PHP_SELF']} {$this->getName()} --mode={$mode}");
            $process->run();

            $result = json_decode($process->getOutput(), true);
            $rows['header'][] = strtoupper($mode);
            $rows['query_count'][] = $result['query_count'];
            $rows['duration'][] = $result['duration'];
            $rows['memory'][] = $result['memory'];
            $sqls[$mode] = $result['sqls'];
        }

        $output->writeln('## Summary');
        $table = new Table($output);
        $table
            ->setHeaders([$rows['header']])
            ->setRows([
                $rows['duration'],
                $rows['memory'],
                $rows['query_count'],
            ])
            ->render();
        ;

        $output->writeln('');
        $output->writeln('## SQL (first 5)');

        foreach ($sqls as $mode => $sqlArray) {
            $output->writeln('');
            $output->writeln('### ' . strtoupper($mode));
            foreach ($sqlArray as $i => $sql) {
                $no = $i + 1;
                $output->writeln("{$no}. " . $sql);
            }
        }
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if ($input->getOption('mode') === null) {
            return $this->executeAllMode($input, $output);
        }

        $specifyMode = $input->getOption('mode');
        if (!array_key_exists($specifyMode, static::$modeFindMethodMap)) {
            throw new \Exception('Unknown mode: ' . $specifyMode);
        }

        $findMethod = static::$modeFindMethodMap[$specifyMode];
        $doctrine = $this->getDoctrine();
        $customerRepository = $doctrine->getRepository('AppBundle:Customer');
        $stopwatch = $this->getStopwatch();

        $stopwatch->start('find');
        $customer = $customerRepository->$findMethod('Target Customer');
        $findEvent = $stopwatch->stop('find');

        $stopwatch->start('operation');
        $operationResult = $this->operation($customer);
        $operationEvent = $stopwatch->stop('operation');

        $output->writeln(json_encode([
            'operation_result' => $operationResult,
            'query_count' => $this->countExecutedQueries(),
            'duration' => sprintf(
                '%s (find: %s, operation: %s)',
                $findEvent->getDuration() + $operationEvent->getDuration(),
                $findEvent->getDuration(),
                $operationEvent->getDuration()
            ),
            'memory' => sprintf(
                '%d (find: %d, operation: %d)',
                $operationEvent->getMemory() / 1024 / 1024,
                $findEvent->getMemory() / 1024 / 1024,
                $operationEvent->getMemory() / 1024 / 1024
            ),
            'sqls' => $this->getExecutedSqls(),
        ]));
    }
}
