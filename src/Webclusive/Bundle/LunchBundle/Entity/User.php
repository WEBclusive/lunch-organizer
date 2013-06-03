<?php

namespace Webclusive\Bundle\LunchBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * User
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Webclusive\Bundle\LunchBundle\Entity\UserRepository")
 */
class User
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=70)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="hipchatHandle", type="string", length=70)
     */
    private $hipchatHandle;

    /**
     * @var DateState[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="DateState", mappedBy="user", cascade={"persist", "remove", "merge"}, orphanRemoval=true)
     */
    protected $dateStates;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return User
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set hipchatHandle
     *
     * @param string $hipchatHandle
     * @return User
     */
    public function setHipchatHandle($hipchatHandle)
    {
        $this->hipchatHandle = $hipchatHandle;

        return $this;
    }

    /**
     * Get hipchatHandle
     *
     * @return string
     */
    public function getHipchatHandle()
    {
        return $this->hipchatHandle;
    }

    /**
     *
     * @param \DateTime $datetime
     * @return mixed
     */
    public function getStateByDate($datetime)
    {
        $ymd = $datetime->format('Ymd');

        $dateStates = $this->dateStates->filter(
            function ($ds) use ($ymd) {
                return ($ds->getTargetDate()->format('Ymd') == $ymd);
            }
        );

        return ( $dateStates->count() > 0 )? $dateStates->first()->getState() : DateState::STATE_NONE;
    }
}
