<?php

namespace AppBundle\Command;

use Doctrine\DBAL\Logging\DebugStack;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class TestImpossibleLazyCommand extends ContainerAwareCommand
{
    use UtilTrait;

    protected function configure()
    {
        $this
            ->setName('test:impossible-lazy')
            ->setDescription('')
            ->addArgument(
                'entity',
                InputArgument::REQUIRED
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $doctrine = $this->getDoctrine();
        $countryRepository = $doctrine->getRepository('AppBundle:Country');
        $capitalCityRepository = $doctrine->getRepository('AppBundle:CapitalCity');

        if ($input->getArgument('entity') === 'country') {
            $entity = $countryRepository->findOneByName('Japan');
        } else {
            $entity = $capitalCityRepository->findOneByName('Tokyo');
        }

        dump([
            'entity' => $entity,
            'sqls'   => $this->getExecutedSqls()
        ]);
    }
}
