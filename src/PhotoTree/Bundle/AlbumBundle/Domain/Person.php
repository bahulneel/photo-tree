<?php
namespace PhotoTree\Bundle\AlbumBundle\Domain;

use Doctrine\Common\Collections\ArrayCollection;
use PhotoTree\Bundle\AlbumBundle\Domain\Event\Event;
use PhotoTree\Bundle\AlbumBundle\Domain\Event\Birth;
use PhotoTree\Bundle\AlbumBundle\Domain\Event\Death;
use PhotoTree\Bundle\AlbumBundle\Domain\Event\Participant\Participant;
use PhotoTree\Bundle\AlbumBundle\Domain\Event\Participant\Child;
use PhotoTree\Bundle\AlbumBundle\Domain\Event\Participant\Deceased;

class Person extends Individual
{
    /**
     *
     * @var ArrayCollection
     */
    private $roles;

    public function __construct()
    {
        $this->roles = new ArrayCollection;
    }

    /**
     * Participates in an event
     *
     * @param Event $event
     * @param Participant $role
     */
    public function participate(Event $event, Participant $role)
    {
        $this->checkConstraints($event, $role);

        $role->setPerson($this);
        $event->addParticipant($role);
        $this->roles->add($role);
    }

    public function checkConstraints(Event $event, Participant $role)
    {
        if (
            $event instanceof Birth &&
            $role instanceof Child &&
            $this->getBirth() instanceof Birth
        ) {
            throw new Exception\DomainException('Cannot be born more than once');
        }

        if (
            $event instanceof Death &&
            $role instanceof Deceased &&
            null !== $this->getDeath()
        ) {
            throw new Exception\DomainException('Cannot die more than once');
        }

        $birth = $this->getBirth();
        if ($birth && $event->isBefore($birth)) {
            throw new Exception\DomainException('Cannot have events before birth');
        }

        $death = $this->getDeath();
        if ($death && $event->isAfter($death)) {
            throw new Exception\DomainException('Cannot have events after death');
        }
    }

    /**
     * Gets the roles the person has played in events
     *
     * @return ArrayCollection
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * Get the persons roles by a specific type
     *
     * @return array
     */
    public function getRolesByType($type)
    {
        $roles = array();
        foreach ($this->getRoles() as $role) {
            if (is_a($role, $type)) {
                $roles[] = $role;
            }
        }
        return $roles;
    }

    public function getChildren()
    {
        $children = array();
        $parentRoles = $this->getRolesByType(__NAMESPACE__ . '\Event\Participant\AParent');

        foreach ($parentRoles as $role) {
            $children[] = $role->getChild()->getPerson();
        }

        return $children;
    }

    public function getBirth()
    {

        $childRoles = $this->getRolesByType(__NAMESPACE__ . '\Event\Participant\Child');
        /* @var $role Event\Participant\Child */
        foreach ($childRoles as $role) {
            if ($role->getEvent() instanceof Birth) {
                return $role->getEvent();
            }
        }
        return null;
    }

    public function getDeath()
    {
        $deathRoles = $this->getRolesByType(__NAMESPACE__ . '\Event\Participant\Deceased');
        if (count($deathRoles)) {
            return $deathRoles[0]->getEvent();
        }
        return null;
    }

    public function getBirthParents()
    {
        $parents = array();

        $birth = $this->getBirth();

        foreach ($birth->getParents() as $parent) {
            $parents[] = $parent->getPerson();
        }

        return $parents;
    }
}
