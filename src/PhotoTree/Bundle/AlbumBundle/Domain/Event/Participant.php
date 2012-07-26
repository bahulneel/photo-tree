<?php
namespace PhotoTree\Bundle\AlbumBundle\Domain\Event;

use PhotoTree\Bundle\AlbumBundle\Domain\Person;

class Participant
{
    /**
     * The participating person
     *
     * @var Person
     */
    private $person = null;

    /**
     * The role the participant is playing
     *
     * @var Role
     */
    private $role = null;

    public function __construct(Person $person)
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
     * Sets the role of the participant
     *
     * @param Role $role
     */
    public function setRole(Role $role)
    {
        $this->role = $role;
    }

    /**
     * Get the current role of the participant
     *
     * @return Role
     */
    public function getRole()
    {
        return $this->role;
    }
}