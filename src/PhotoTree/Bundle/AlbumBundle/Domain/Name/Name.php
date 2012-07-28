<?php
namespace PhotoTree\Bundle\AlbumBundle\Domain\Name;

use PhotoTree\Bundle\AlbumBundle\Domain\Event\Participant\Participant;
use PhotoTree\Bundle\AlbumBundle\Domain\Entity;

class Name extends Entity
{
    /**
     *
     * @var Participant
     */
    private $participant;

    public function setParticipant(Participant $participant)
    {
        $this->participant = $participant;
    }
}
