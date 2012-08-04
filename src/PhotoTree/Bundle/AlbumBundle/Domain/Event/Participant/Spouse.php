<?php
namespace PhotoTree\Bundle\AlbumBundle\Domain\Event\Participant;

use PhotoTree\Bundle\AlbumBundle\Domain\Name\Name;

class Spouse extends Participant implements HasNameInterface
{
    public function setName(Name $name)
    {
        $name->setParticipant($this);
        $this->name = $name;
    }

    public function getSpouse()
    {
        $spouses = $this->getEvent()->getParticipantsByType(__NAMESPACE__ . '\Spouse');

        foreach ($spouses as $spouse) {
            if ($spouse !== $this) {
                return $spouse;
            }
        }

        return null;
    }
}
