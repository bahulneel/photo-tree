<?php
namespace PhotoTree\Bundle\AlbumBundle\Domain\Event;

use PhotoTree\Bundle\AlbumBundle\Domain\Exception\DomainException;

class Birth extends Event
{
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
        if (0 === count($children)) {
            return null;
        }
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

    public function addParticipant(Participant\Participant $participant)
    {
        parent::addParticipant($participant);
        $child = $this->getChild();
        if (!$child) {
            return;
        }
        $parents = $this->getParents();
        if (0 === count($parents)) {
            return;
        }
        foreach ($parents as $parent) {
            $lineages = $parent->getLineages();
            foreach ($lineages as $lineage) {
                if ($lineage->isPassed($parent, $child)) {
                    $child->addLineage($lineage);
                }
            }
        }
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
