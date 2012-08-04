<?php
namespace PhotoTree\Bundle\AlbumBundle\Domain\Event\Participant;

use PhotoTree\Bundle\AlbumBundle\Domain\Name\Name;
use Doctrine\Common\Collections\ArrayCollection;
use PhotoTree\Bundle\AlbumBundle\Domain\Lineage\Lineage;
use PhotoTree\Bundle\AlbumBundle\Domain\Person;

class Child extends Participant implements HasNameInterface
{
    /**
     *
     * @var ArrayCollection
     */
    private $lineages;

    public function __construct()
    {
        $this->lineages = new ArrayCollection;
    }

    public function setName(Name $name)
    {
        $name->setParticipant($this);
        $this->name = $name;
    }

    public function getParents()
    {
        return $this->getEvent()->getParents();
    }

    public function addLineage(Lineage $lineage)
    {
        if ($this->lineages->contains($lineage)) {
            return;
        }
        $this->lineages->add($lineage);
    }

    public function getLineages()
    {
        return $this->lineages;
    }

}
