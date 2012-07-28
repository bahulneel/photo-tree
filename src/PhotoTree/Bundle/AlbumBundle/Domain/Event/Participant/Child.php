<?php
namespace PhotoTree\Bundle\AlbumBundle\Domain\Event\Participant;

class Child extends Participant
{
    public function getParents()
    {
        return $this->getEvent()->getParents();
    }
}
