<?php
namespace PhotoTree\Bundle\AlbumBundle\Domain\Event;

use PhotoTree\Bundle\AlbumBundle\Domain\Exception\DomainException;

class Birth extends Event
{
    /**
     * The Parents
     *
     * @var array
     */
    private $parents = array();

    /**
     * The Child
     *
     * @var Participant\Child
     */
    private $child;

    /**
     * Sets the birth child
     *
     * @param Participant\Child $child
     */
    public function setChild(Participant\Child $child)
    {
        if ($this->child instanceof Participant\Child) {
            throw new DomainException('Cannot have more than one child');
        }
        $this->child = $child;
    }

    /**
     * Get the child of the birth
     *
     * @return Participant\Child
     */
    public function getChild()
    {
        return $this->child;
    }

    /**
     * Adds a parent to the birth
     *
     * @param Participant\AParent $parent
     */
    public function addParent(Participant\AParent $parent)
    {
        $parents = $this->getParents();
        if (count($parents) == 2) {
            throw new DomainException('Cannot have more than 2 birth parents');
        }
        foreach ($parents as $currentParent) {
            if ($currentParent === $parent) {
                throw new DomainException('Cannot add same parent twice');
            }
        }
        $this->parents[] = $parent;
    }

    /**
     * Gets the parents of the birth
     *
     * @return array
     */
    public function getParents()
    {
        return $this->parents;
    }
}
