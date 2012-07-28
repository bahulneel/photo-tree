<?php
namespace PhotoTree\Bundle\AlbumBundle\Domain\Lineage;

use Mockery as m;

class LineageTest extends \PHPUnit_Framework_TestCase
{
    public function testAnLineageIsAnEntity()
    {
        $lineage = new Lineage;
        $this->assertInstanceOf('PhotoTree\Bundle\AlbumBundle\Domain\Entity', $lineage, 'An Lineage is an entity');
    }

    public function testLineageIsAlwaysPassed()
    {
        $parent = m::mock('PhotoTree\Bundle\AlbumBundle\Domain\Event\Participant\AParent');
        $child = m::mock('PhotoTree\Bundle\AlbumBundle\Domain\Event\Participant\Child');

        $lineage = new Lineage;

        $this->assertTrue($lineage->isPassed($parent, $child));
    }

    public function testLineageCanHaveAValue()
    {
        $value = 'someValue';
        $lineage = new Lineage($value);

        $this->assertEquals($value, $lineage->getValue());
    }
}