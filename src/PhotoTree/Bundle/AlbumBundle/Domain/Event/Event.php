<?php
namespace PhotoTree\Bundle\AlbumBundle\Domain\Event;

use Doctrine\Common\Collections\ArrayCollection;
use PhotoTree\Bundle\AlbumBundle\Domain\Exception\DomainException;
use PhotoTree\Bundle\AlbumBundle\Domain\Event\Participant\Participant;
use PhotoTree\Bundle\AlbumBundle\Domain\Entity;

class Event extends Entity
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

    /**
     *
     * @var ArrayCollection
     */
    private $sources;

    /**
     *
     * @var \DateTime
     */
    private $date;

    public function __construct()
    {
        $this->participants = new ArrayCollection;
        $this->sources = new ArrayCollection;
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
        $participant->setEvent($this);
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

    /**
     * Get the events participants by a specific type
     *
     * @return array
     */
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

    /**
     * Checks if a new participant satisfies a constraint
     *
     * @param array $constraint
     * @param Participant $participant
     * @return boolean
     */
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

    /**
     * Sets the events date
     *
     * @param \DateTime $date
     */
    public function setDate(\DateTime $date)
    {
        $this->date = $date;
    }

    /**
     * Gets the events date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    public function getInterval(Event $event)
    {
        if (!$this->date) {
            return null;
        }
        $eventDate = $event->getDate();
        if (!$eventDate) {
            return null;
        }

        return $eventDate->diff($this->date);
    }

    /**
     *
     * @param Event $event
     * @return boolean
     */
    public function isBefore(Event $event)
    {
        $diff = $this->getInterval($event);
        if (!$diff) {
            return false;
        }
        $invert = $diff->invert;
        return ($invert === 1);
    }
    /**
     *
     * @param Event $event
     * @return boolean
     */
    public function isAfter(Event $event)
    {
        $diff = $this->getInterval($event);
        if (!$diff) {
            return false;
        }
        $invert = $diff->invert;
        return ($invert === 0);
    }

    public function addSource(Source $source)
    {
        if ($this->sources->contains($source)) {
            return;
        }
        $source->setEvent($this);
        $this->sources->add($source);
    }

    public function getSources()
    {
        return $this->sources;
    }
}
