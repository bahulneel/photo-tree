<?php
namespace PhotoTree\Bundle\AlbumBundle\Domain\Event;

use PhotoTree\Bundle\AlbumBundle\Domain\Exception\DomainException;

class Marriage extends Event
{
    /**
     * Adds a spouse to the marriage
     *
     * @param Participant\Spouse $spouse
     */
    public function addSpouse(Participant\Spouse $spouse)
    {
        $this->addParticipant($spouse);
    }

    /**
     * Gets the spouses of the marriage
     *
     * @return array
     */
    public function getSpouses()
    {
        $spouses = $this->getParticipantsByType(__NAMESPACE__ . '\Participant\Spouse');
        return $spouses;
    }

    /**
     * {inheritdoc}
     * @return array
     */
    public function loadConstraints()
    {
        return array(
            array(
                'type' => __NAMESPACE__ . '\Participant\Spouse',
                'max' => 2
            )
        );
    }
}
