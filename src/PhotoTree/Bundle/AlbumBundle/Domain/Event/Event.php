<?php
namespace PhotoTree\Bundle\AlbumBundle\Domain\Event;

use Doctrine\Common\Collections\ArrayCollection;
use PhotoTree\Bundle\AlbumBundle\Domain\Exception\DomainException;
use PhotoTree\Bundle\AlbumBundle\Domain\Event\Participant\Participant;

class Event
{
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
        /* @var $currentParticipant Event\Participant */
        foreach ($this->participants as $currentParticipant) {
            if ($participant->getPerson() == $currentParticipant->getPerson()) {
                throw new DomainException('A person can only participate once in an event');
            }
        }
        $this->participants->add($participant);
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
}
