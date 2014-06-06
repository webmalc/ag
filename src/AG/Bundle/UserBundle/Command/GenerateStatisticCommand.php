<?php

namespace AG\Bundle\UserBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use AG\Bundle\UserBundle\Document\Statistics;

class GenerateStatisticCommand extends ContainerAwareCommand
{
    /**
     * @see Command
     */
    protected function configure()
    {
        $this
            ->setName('ag:user:stats')
            ->setDescription('Generate statistic')
        ;
    }

    /**
     * @see Command
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $dm = $this->getContainer()->get('doctrine_mongodb')->getManager();
        $stats = $dm->getRepository('AGUserBundle:Statistics')->findAll();
        $date = new \DateTime();
        
        //Get stats
        if(empty($stats[0])) {
            $stats = new Statistics(1246, 2, 238, 1);
            $dm->persist($stats);
            $dm->flush();
        } else {
            $stats = $stats[0];
        }
        
        //Add today fileds
        if($date->format('H') < 3) {
            $stats->setCarsToday(0)
                  ->setMessagesToday(0)
            ;
        } else {
            $cars = rand(0, 3);
            $messages = rand(0, 2);
            $stats->addCarsToday($cars)
                  ->addMessagesToday($messages)
            ;
        }
        
        $dm->persist($stats);
        $dm->flush();
        
        $output->writeln('ок');
    }
}