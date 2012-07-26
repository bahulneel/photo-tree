<?php
namespace PhotoTree\Bundle\AlbumBundle\Domain;

use Doctrine\Common\Collections\ArrayCollection;

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
     * @param Event\Participant $participant
     */
    public function addParticipant(Event\Participant $participant)
    {
        /* @var $currentParticipant Event\Participant */
        foreach ($this->participants as $currentParticipant) {
            if ($participant->getPerson() == $currentParticipant->getPerson()) {
                return;
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
