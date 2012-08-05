<?php
namespace PhotoTree\Bundle\AlbumBundle\Domain\Lineage;

use PhotoTree\Bundle\AlbumBundle\Domain\Entity;
use PhotoTree\Bundle\AlbumBundle\Domain\Event\Participant\AParent;
use PhotoTree\Bundle\AlbumBundle\Domain\Event\Participant\Child;

class Lineage extends Entity
{
    /**
     *
     * @var string
     */
    private $value;

    protected static $NAME = 'Lineage';
    
    public function __construct($value = null)
    {
        $this->value = $value;
    }

    /**
     *
     * @param AParent $parent
     * @param Child $child
     * @return boolean
     */
    public function isPassed(AParent $parent, Child $child)
    {
        return true;
    }

    public function getValue()
    {
        return $this->value;
    }
    
    public function getDisplayName()
    {
        return static::$NAME;
    }
}
