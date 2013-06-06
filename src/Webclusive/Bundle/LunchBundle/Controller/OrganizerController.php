<?php

namespace Webclusive\Bundle\LunchBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Webclusive\Bundle\LunchBundle\Entity\UserRepository;

class OrganizerController extends Controller
{
    /**
     * @Route("/", name="organizer")
     * @Template()
     */
    public function indexAction()
    {
        /** @var UserRepository $userRepo */
        $userRepo = $this->getDoctrine()->getRepository('WebclusiveLunchBundle:User');
        $usersAndCounts = $userRepo->getAllWithCurrentLunchCount();

        $users = array();
        $counts = array();
        foreach ($usersAndCounts as $userAndCount) {

            $user  = $userAndCount[0];
            $count = $userAndCount['totalCount'];

            $users[] = $user;
            $counts[$user->getId()] = $count;

        }

        $renderDates = new \DatePeriod(new \DateTime('-2 days'), new \DateInterval('P1D'), 60);

        return array(
            'users'       => $users,
            'counts'      => $counts,
            'renderDates' => $renderDates
        );

    }


}
