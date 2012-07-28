<?php
namespace PhotoTree\Bundle\AlbumBundle\Domain\Event\Participant;

class AParent extends Participant
{
    public function getChild()
    {
        return $this->getEvent()->getChild();
    }
}
