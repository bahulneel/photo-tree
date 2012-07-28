<?php
namespace PhotoTree\Bundle\AlbumBundle\Domain\Lineage;

use PhotoTree\Bundle\AlbumBundle\Domain\Event\Participant\AParent;
use PhotoTree\Bundle\AlbumBundle\Domain\Event\Participant\Child;

class Maternal extends Lineage
{
    /**
     *
     * @param AParent $parent
     * @param Child $child
     * @return boolean
     */
    public function isPassed(AParent $parent, Child $child)
    {
        return $parent->isMother();
    }
}