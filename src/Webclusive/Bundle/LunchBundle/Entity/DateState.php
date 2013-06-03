<?php

namespace Webclusive\Bundle\LunchBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DateState
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Webclusive\Bundle\LunchBundle\Entity\DateStateRepository")
 */
class DateState
{
    const STATE_LUNCH  = 'lunch';
    const STATE_TOWELS = 'towel';
    const STATE_ABSENT = 'absnt';
    const STATE_NONE   = 'none';

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="targetDate", type="date")
     */
    private $targetDate;

    /**
     * @var string
     *
     * @ORM\Column(name="state", type="string", length=5)
     */
    private $state;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User", cascade={"all"}, fetch="LAZY", inversedBy="dateStates")
     */
    protected $user;

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
     * Set targetDate
     *
     * @param \DateTime $targetDate
     * @return DateState
     */
    public function setTargetDate($targetDate)
    {
        $this->targetDate = $targetDate;

        return $this;
    }

    /**
     * Get targetDate
     *
     * @return \DateTime
     */
    public function getTargetDate()
    {
        return $this->targetDate;
    }

    /**
     * Set state
     *
     * @param string $state
     * @return DateState
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param \Webclusive\Bundle\LunchBundle\Entity\User $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return \Webclusive\Bundle\LunchBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

}
