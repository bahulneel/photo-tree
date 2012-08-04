<?php
namespace PhotoTree\Bundle\AlbumBundle\Domain\Event\Participant;

use PhotoTree\Bundle\AlbumBundle\Domain\Person;
use PhotoTree\Bundle\AlbumBundle\Domain\Event\Event;

class Participant
{
    /**
     *
     * @var Name
     */
    protected $name = null;
    
    /**
     * The participating person
     *
     * @var Person
     */
    private $person = null;

    /**
     * The event participated in
     *
     * @var Event
     */
    private $event = null;
	
    /**
     * Sets this participating person
     *
     * @param Person $person
     */
    public function setPerson(Person $person)
    {
        $this->person = $person;
    }

    /**
     * Gets the person participating
     *
     * @return Person
     */
    public function getPerson()
    {
        return $this->person;
    }

    /**
     * Sets the event participated in
     *
     * @param Event $event
     */
    public function setEvent(Event $event)
    {
        $this->event = $event;
    }

    /**
     * Gets the event participated in
     *
     * @return Event
     */
    public function getEvent()
    {
        return $this->event;
    }
    
    
    public function getName()
    {
        return $this->name;
    }
}
