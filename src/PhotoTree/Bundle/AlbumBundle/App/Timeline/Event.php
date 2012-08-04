<?php
namespace PhotoTree\Bundle\AlbumBundle\App\Timeline;

use PhotoTree\Bundle\AlbumBundle\Domain\Event\Participant\Participant;

class Event
{
    private $participant;
    
    public function __construct(Participant $participant)
    {
        $this->participant = $participant;
    }
    
    public function getDate()
    {
        return $this->participant->getEvent()->getDate();
    }
    
    public function getDescriptor()
    {
        $event = strtolower(basename(get_class($this->participant->getEvent())));
        $role = strtolower(basename(get_class($this->participant)));
        return $event . '.' . $role;
    }
    
    public function getTemplate()
    {
        return 'PhotoTreeAlbumBundle:Person:timeline.' . $this->getDescriptor() . '.html.twig';
    }
    
    public function getEvent()
    {
        return $this->participant->getEvent();
    }   
    public function getPerson()
    {
        return $this->participant->getPerson();
    }
    
    public function getParticipant()
    {
        return $this->participant;
    }
}
