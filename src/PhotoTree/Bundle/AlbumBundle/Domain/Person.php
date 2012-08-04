<?php
namespace PhotoTree\Bundle\AlbumBundle\Domain;

use Doctrine\Common\Collections\ArrayCollection;
use PhotoTree\Bundle\AlbumBundle\App;
use PhotoTree\Bundle\AlbumBundle\Domain\Event\Event;
use PhotoTree\Bundle\AlbumBundle\Domain\Event\Birth;
use PhotoTree\Bundle\AlbumBundle\Domain\Event\Death;
use PhotoTree\Bundle\AlbumBundle\Domain\Event\Participant\Participant;
use PhotoTree\Bundle\AlbumBundle\Domain\Event\Participant\Child;
use PhotoTree\Bundle\AlbumBundle\Domain\Event\Participant\Deceased;
use PhotoTree\Bundle\AlbumBundle\Domain\Name\Name;
use PhotoTree\Bundle\AlbumBundle\Domain\Gender\AbstractGender;

class Person extends Individual
{
    /**
     *
     * @var ArrayCollection
     */
    private $roles;

    /**
     *
     * @var AbstractGender
     */
    private $gender;

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
            $this->getDeath() instanceof Death
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

    /**
     *
     * @return Birth
     */
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

    /**
     *
     * @return Death
     */
    public function getDeath()
    {
        $deathRoles = $this->getRolesByType(__NAMESPACE__ . '\Event\Participant\Deceased');
        if (count($deathRoles)) {
            return $deathRoles[0]->getEvent();
        }
        return null;
    }

    /**
     *
     * @return array
     */
    public function getBirthParents()
    {
        $parents = array();

        $birth = $this->getBirth();

        foreach ($birth->getParents() as $parentRole) {
            $parents[] = $parentRole->getPerson();
        }

        return $parents;
    }

    /**
     *
     * @return Person
     */
    public function getSpouse()
    {
        $spouseRoles = $this->getRolesByType(__NAMESPACE__ . '\Event\Participant\Spouse');

        $spouse = null;

        foreach ($spouseRoles as $spouseRole) {
            $currentSpouse = $spouseRole->getSpouse();
            if (!$currentSpouse) {
                continue;
            }
            if ($currentSpouse->getPerson()->getDeath()) {
                continue;
            }
            if (null === $spouse) {
                $spouse = $currentSpouse;
                continue;
            }
            if ($spouseRole->getEvent()->isAfter($spouse->getEvent())) {
                $spouse = $currentSpouse;
            }
        }

        if ($spouse) {
            return $spouse->getPerson();
        }

        return null;
    }

    /**
     *
     * @return array
     */
    public function getSpouses()
    {
        $spouses = array();

        $spouseRoles = $this->getRolesByType(__NAMESPACE__ . '\Event\Participant\Spouse');

        foreach ($spouseRoles as $spouseRole) {
            $spouse = $spouseRole->getSpouse();
            if ($spouse) {
                $spouses[] = $spouse->getPerson();
            }
        }

        return $spouses;
    }

    /**
     *
     * @return Name
     */
    public function getBirthName()
    {
        $birth = $this->getBirth();
        if (!$birth) {
            return null;
        }
        return $birth->getChild()->getName();
    }

    /**
     *
     * @return Name
     */
    public function getMarriedName()
    {
        $spouseRoles = $this->getRolesByType(__NAMESPACE__ . '\Event\Participant\Spouse');

        $name = null;

        foreach ($spouseRoles as $spouseRole) {
            if (null === $name) {
                $name = $spouseRole->getName();
            }
        }

        return $name;
    }

    /**
     *
     * @return Name
     */
    public function getCurrentName()
    {
        $name = $this->getBirthName();
        $marriedName = $this->getMarriedName();

        if ($marriedName) {
            $name = $marriedName;
        }

        return $name;
    }

    public function getSiblings()
    {
        $parents = $this->getBirth()->getParents();
        $siblings = new ArrayCollection();
        
        foreach ($parents as $parent) {
            $children = $parent->getPerson()->getChildren();
            foreach ($children as $child) {
                if ($this !== $child && !$siblings->contains($child)) {
                    $siblings->add($child);
                }
            }
        }
        return $siblings;
    }
    
    public function getLineages()
    {
        $birth = $this->getBirth();

        if (!$birth) {
            return array();
        }

        $child = $birth->getChild();

        if (!$child) {
            return array();
        }

        return $child->getLineages();
    }

    public function setGender(AbstractGender $gender)
    {
        $this->gender = $gender;
    }

    public function getGender()
    {
        return $this->gender;
    }
    
    public function getTimeline()
    {
        $roles = $this->getRoles();
        $timeline = new App\Timeline;
        $timeline->addRoles($roles);
        return $timeline;
    }
}
