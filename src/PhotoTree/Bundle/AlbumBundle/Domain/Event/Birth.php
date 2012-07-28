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
        $this->addParticipant($child);
    }

    /**
     * Get the child of the birth
     *
     * @return Participant\Child
     */
    public function getChild()
    {
        $children = $this->getParticipantsByType(__NAMESPACE__ . '\Participant\Child');
        return $children[0];
    }

    /**
     * Adds a parent to the birth
     *
     * @param Participant\AParent $parent
     */
    public function addParent(Participant\AParent $parent)
    {
        $this->addParticipant($parent);
    }

    /**
     * Gets the parents of the birth
     *
     * @return array
     */
    public function getParents()
    {
        $parents = $this->getParticipantsByType(__NAMESPACE__ . '\Participant\AParent');
        return $parents;
    }

    /**
     * {inheritdoc}
     * @return array
     */
    public function loadConstraints()
    {
        return array(
            array(
                'type' => __NAMESPACE__ . '\Participant\Child',
                'max' => 1,
                'message' => 'A birth can have only one child'
            ),
            array(
                'type' => __NAMESPACE__ . '\Participant\AParent',
                'max' => 2
            )
        );
    }
}
