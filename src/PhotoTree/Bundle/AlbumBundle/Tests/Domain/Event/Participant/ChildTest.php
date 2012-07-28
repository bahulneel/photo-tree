<?php
namespace PhotoTree\Bundle\AlbumBundle\Domain\Event\Participant;

use Mockery as m;

class ChildTest extends \PHPUnit_Framework_TestCase
{
    public function testChildIsAParicipant()
    {
        $child = new Child;
        $this->assertInstanceOf('PhotoTree\Bundle\AlbumBundle\Domain\Event\Participant\Participant', $child);
    }

    public function testChildCanHaveAName()
    {
        $name = m::mock('PhotoTree\Bundle\AlbumBundle\Domain\Name\Name');
        $child = new Child;
        $name->shouldReceive('setParticipant')->once()->with($child);
        $child->setName($name);
        $this->assertSame($name, $child->getName());
    }

    public function testChildCanHaveALineage()
    {
        $lineage = m::mock('PhotoTree\Bundle\AlbumBundle\Domain\Lineage\Lineage');

        $child = new Child;

        $child->addLineage($lineage);

        $lineages = $child->getLineages();

        $this->assertSame($lineage, $lineages[0]);
    }

    public function testChildCanHaveTheSameLineageOnlyOnce()
    {
        $lineage = m::mock('PhotoTree\Bundle\AlbumBundle\Domain\Lineage\Lineage');

        $child = new Child;

        $child->addLineage($lineage);
        $child->addLineage($lineage);

        $lineages = $child->getLineages();

        $this->assertEquals(1, count($lineages), 'One one lineage appies');
        $this->assertSame($lineage, $lineages[0]);
    }
}