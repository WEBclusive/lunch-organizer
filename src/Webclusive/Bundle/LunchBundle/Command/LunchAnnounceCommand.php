<?php

namespace Webclusive\Bundle\LunchBundle\Command;

use HipChat\HipChat;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Webclusive\Bundle\LunchBundle\Entity\DateState;

class LunchAnnounceCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('lunch:announce')
            ->setDescription('Announces the person responsible for Lunch.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $dsRepo = $this->getContainer()->get('doctrine')->getRepository('WebclusiveLunchBundle:DateState');

        $date = new \DateTime('now');

        $output->writeln(sprintf("Lunch notifier execution for %s", $date->format('d-m-Y')));

        $states = $dsRepo->findBy(array('targetDate' => $date));

        if (count($states) == 0) {
            $this->sendMessage("Oh dear, seems no one has been selected to handle lunch, i'm calling for pizza.");
            $output->writeln('No one found.');
        }

        foreach ($states as $state) {
            /** @var $state DateState */
            if ($state->getState() == DateState::STATE_LUNCH) {
                $this->sendMessage(sprintf("Hey @all, call out @%s, he is responsible for the chow today.", $state->getUser()->getHipchatHandle()));
                $output->writeln('Lunch: '.$state->getUser()->getName());
            }

            if ($state->getState() == DateState::STATE_TOWELS) {
                $this->sendMessage(sprintf("By the way @all, @%s is on laundry duty.", $state->getUser()->getHipchatHandle()));
                $output->writeln('Towels: '.$state->getUser()->getName());
            }
        }
    }

    protected function sendMessage($msg)
    {
        $api = $this->getContainer()->get('hipchat');

        $room = 'OffTopic';
        $from = 'OMNOMNOM';

        $api->message_room($room, $from, $msg, true, HipChat::COLOR_YELLOW, HipChat::FORMAT_TEXT );
    }
}
