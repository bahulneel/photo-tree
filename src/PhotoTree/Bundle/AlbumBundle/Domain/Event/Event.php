<?php
namespace PhotoTree\Bundle\AlbumBundle\Domain\Event;

use Doctrine\Common\Collections\ArrayCollection;
use PhotoTree\Bundle\AlbumBundle\Domain\Exception\DomainException;
use PhotoTree\Bundle\AlbumBundle\Domain\Event\Participant\Participant;

class Event
{
    /**
     *
     * @var array
     */
    protected $constraints = null;

    /**
     *
     * @var ArrayCollection
     */
    private $participants;

    public function __construct()
    {
        $this->participants = new ArrayCollection;
    }

    /**
     * Adds a participant to the event
     *
     * @param Participant $participant
     */
    public function addParticipant(Participant $participant)
    {
        $this->checkConstraints($participant);

        /* @var $currentParticipant Event\Participant */
        foreach ($this->participants as $currentParticipant) {
            if ($participant->getPerson() == $currentParticipant->getPerson()) {
                throw new DomainException('A person can only participate once in an event');
            }
        }
        $this->participants->add($participant);
    }

    /**
     * Checks a pendind participant against the contraints for the event
     *
     * @param Participant $participant
     */
    public function checkConstraints(Participant $participant)
    {
        $contraints = $this->getConstraints();
        foreach ($contraints as $constraint) {
            if (!$this->isSatisfiedBy($constraint, $participant)) {
                $message = 'Unsatisfied constraint';
                if (isset($constraint['message'])) {
                    $message = $constraint['message'];
                }
                throw new DomainException($message);
            }
        }
    }

    /**
     *
     * @return array
     */
    public function getConstraints()
    {
        if (null === $this->constraints) {
            $this->constraints = $this->loadConstraints();
        }
        return $this->constraints;
    }

    /**
     * Returns a new set of constraints
     *
     * @return array
     */
    public function loadConstraints()
    {
        return array();
    }

    /**
     * Get the events participants
     *
     * @return ArrayCollection
     */
    public function getParticipants()
    {
        return $this->participants;
    }

    public function getParticipantsByType($type)
    {
        $participants = array();
        foreach ($this->getParticipants() as $participant) {
            if (is_a($participant, $type)) {
                $participants[] = $participant;
            }
        }
        return $participants;
    }

    public function isSatisfiedBy(array $constraint, Participant $participant)
    {
        $type = $constraint['type'];
        $max = $constraint['max'];
        if (!is_a($participant, $type)) {
            return true;
        }
        $currentParticipants = $this->getParticipantsByType($type);
        if ($max > count($currentParticipants)) {
            return true;
        }
        return false;
    }
}
