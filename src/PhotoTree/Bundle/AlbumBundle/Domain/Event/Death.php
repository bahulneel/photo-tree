<?php
namespace PhotoTree\Bundle\AlbumBundle\Domain\Event;

use PhotoTree\Bundle\AlbumBundle\Domain\Exception\DomainException;

class Death extends Event
{
    /**
     * Sets the death deceased
     *
     * @param Participant\Deceased $deceased
     */
    public function setDeceased(Participant\Deceased $deceased)
    {
        $this->addParticipant($deceased);
    }

    /**
     * Get the deceased of the death
     *
     * @return Participant\Deceased
     */
    public function getDeceased()
    {
        $deceasedren = $this->getParticipantsByType(__NAMESPACE__ . '\Participant\Deceased');
        return $deceasedren[0];
    }

    /**
     * {inheritdoc}
     * @return array
     */
    public function loadConstraints()
    {
        return array(
            array(
                'type' => __NAMESPACE__ . '\Participant\Deceased',
                'max' => 1,
                'message' => 'A death can have only one deceased'
            )
        );
    }
}
