<?php

namespace Webclusive\Bundle\LunchBundle\Command;

use HipChat\HipChat;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Webclusive\Bundle\LunchBundle\Entity\DateState;

class LunchAnnounceCommand extends ContainerAwareCommand
{
    /**
     * @var InputInterface
     */
    protected $input;

    /**
     * @var OutputInterface
     */
    protected $output;

    protected function configure()
    {
        $this
            ->setName('lunch:announce')
            ->setDescription('Announces the person responsible for Lunch.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $this->output = $output;

        $date = new \DateTime('now');
        $output->writeln(sprintf("Lunch notifier execution for %s", $date->format('d-m-Y')));

        $todayResponsibles = $this->processToday();
        $tomorrowResponsibles = $this->processTomorrow();

        if ($todayResponsibles === false) {
            $this->sendMessage("Oh dear, seems no one has been selected to handle lunch, i'm calling for pizza.");
            $output->writeln('No one found.');
        }

        if (isset($todayResponsibles[DateState::STATE_LUNCH])) {
            $lunchUser = $todayResponsibles[DateState::STATE_LUNCH];
            $this->sendMessage(sprintf("Hey @all, call out @%s, he is responsible for the chow today. (chompy)", $lunchUser->getHipchatHandle()));
            $output->writeln('Lunch: '.$lunchUser->getName());
        }

        if (isset($todayResponsibles[DateState::STATE_TOWELS])){
            $towelsUser = $todayResponsibles[DateState::STATE_TOWELS];
            $this->sendMessage(sprintf("By the way @all, @%s is on laundry duty.", $towelsUser->getHipchatHandle()));
            $output->writeln('Towels: '.$towelsUser->getName());
        }

        if ($tomorrowResponsibles === false) {
            $this->sendMessage("Looks like we will all starve tomorrow, no one is responsible for lunch. (tableflip)");
            $output->writeln('No one found for tomorrow');
        }

        if (isset($tomorrowResponsibles[DateState::STATE_LUNCH])) {
            $lunchUser = $tomorrowResponsibles[DateState::STATE_LUNCH];
            $this->sendMessage(sprintf("Heads up @%s, tomorrow (or monday) is your turn for lunch. (fry)", $lunchUser->getHipchatHandle()));
            $output->writeln('Lunch Tomorrow: '.$lunchUser->getName());
        }

    }

    /**
     * Get's today's people
     *
     * @return array|bool
     */
    protected function processToday()
    {
        $dsRepo = $this->getContainer()->get('doctrine')->getRepository('WebclusiveLunchBundle:DateState');

        $date = new \DateTime('now');

        $states = $dsRepo->findBy(array('targetDate' => $date));

        if (count($states) == 0) {
            return false;
        }

        $responsibles = array();
        foreach ($states as $state) {
            /** @var $state DateState */
            if ($state->getState() == DateState::STATE_LUNCH) {
                $responsibles[$state->getState()] = $state->getUser();
            }

            if ($state->getState() == DateState::STATE_TOWELS) {
                $responsibles[$state->getState()] = $state->getUser();
            }
        }

        return $responsibles;
    }

    /**
     * Get tomorrow's people
     *
     * @return array|bool
     */
    protected function processTomorrow()
    {
        $dsRepo = $this->getContainer()->get('doctrine')->getRepository('WebclusiveLunchBundle:DateState');

        $date = new \DateTime('tomorrow');

        // Skip Weekends
        if ($date->format('N') > 5) {
            $date->modify('next monday');
        }

        $states = $dsRepo->findBy(array('targetDate' => $date));

        if (count($states) == 0) {
            return false;
        }

        $responsibles = array();
        foreach ($states as $state) {
            /** @var $state DateState */
            if ($state->getState() == DateState::STATE_LUNCH) {
                $responsibles[$state->getState()] = $state->getUser();
            }
        }

        return $responsibles;
    }

    /**
     * Send HipChat alerts
     *
     * @param $msg
     */
    protected function sendMessage($msg)
    {
        $api = $this->getContainer()->get('hipchat');

        $room = $this->getContainer()->getParameter('hipchat_room');
        $from = 'OMNOMNOM';

        $api->message_room($room, $from, $msg, true, HipChat::COLOR_YELLOW, HipChat::FORMAT_TEXT );
    }
}
