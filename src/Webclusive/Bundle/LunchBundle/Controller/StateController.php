<?php

namespace Webclusive\Bundle\LunchBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Webclusive\Bundle\LunchBundle\Entity\DateState;

class StateController extends Controller
{
    /**
     * @Route("/state/set", name="set-state")
     */
    public function setAction()
    {
        $userRepo = $this->getDoctrine()->getRepository('WebclusiveLunchBundle:User');
        $dsRepo   = $this->getDoctrine()->getRepository('WebclusiveLunchBundle:DateState');

        $date  = \DateTime::createFromFormat('Ymd', $this->getRequest()->request->get('date'));
        $user  = $this->getRequest()->request->get('user');
        $state = $this->getRequest()->request->get('state');

        $user = $userRepo->find($user);

        if ($user === null) {
            return new Response('Unable to find user', 404);
        }

        $prevStates = $dsRepo->findBy(array('targetDate' => $date, 'user' => $user));

        $newState = (count($prevStates) > 0)? array_shift($prevStates) : new DateState();
        $newState->setState($state);
        $newState->setTargetDate($date);
        $newState->setUser($user);

        $this->getDoctrine()->getManager()->persist($newState);
        $this->getDoctrine()->getManager()->flush();

        return new Response("", 200);
    }

}
