<?php
namespace PhotoTree\Bundle\AlbumBundle\Domain\Event\Participant;

use PhotoTree\Bundle\AlbumBundle\Domain\Gender\Male;
use PhotoTree\Bundle\AlbumBundle\Domain\Gender\Female;

class AParent extends Participant
{
    public function getChild()
    {
        return $this->getEvent()->getChild();
    }

    public function getLineages()
    {
        return $this->getPerson()->getLineages();
    }

    public function isFather()
    {
        if ($this->getPerson()->getGender() instanceof Male) {
            return true;
        }
        return false;
    }

    public function isMother()
    {
        if ($this->getPerson()->getGender() instanceof Female) {
            return true;
        }
        return false;
    }
}
