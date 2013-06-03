<?php

namespace Webclusive\Bundle\LunchBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class OrganizerController extends Controller
{
    /**
     * @Route("/", name="organizer")
     * @Template()
     */
    public function indexAction()
    {

        $users = $this->getDoctrine()->getRepository('WebclusiveLunchBundle:User')->findAll();

        $renderDates = new \DatePeriod(new \DateTime('-2 days'), new \DateInterval('P1D'), 60);


        return array(
            'users' => $users,
            'renderDates' => $renderDates
        );

    }

}
