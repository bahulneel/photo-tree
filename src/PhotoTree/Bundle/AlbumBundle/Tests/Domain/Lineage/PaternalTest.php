<?php
namespace PhotoTree\Bundle\AlbumBundle\Domain\Lineage;

use Mockery as m;

class PaternalTest extends \PHPUnit_Framework_TestCase
{
    public function testAnPaternalIsALineage()
    {
        $lineage = new Paternal;
        $this->assertInstanceOf('PhotoTree\Bundle\AlbumBundle\Domain\Lineage\Lineage', $lineage, 'An Paternal is an entity');
    }

    public function testPaternalIsPassedFromFatherToChildren()
    {
        $parent = m::mock('PhotoTree\Bundle\AlbumBundle\Domain\Event\Participant\AParent', array(
            'isFather' => true
        ));
        $child = m::mock('PhotoTree\Bundle\AlbumBundle\Domain\Event\Participant\Child');

        $lineage = new Paternal;

        $this->assertTrue($lineage->isPassed($parent, $child));
    }

    public function testPaternalIsPassedNotFromMotherToChildren()
    {
        $parent = m::mock('PhotoTree\Bundle\AlbumBundle\Domain\Event\Participant\AParent', array(
            'isFather' => false
        ));
        $child = m::mock('PhotoTree\Bundle\AlbumBundle\Domain\Event\Participant\Child');

        $lineage = new Paternal;

        $this->assertFalse($lineage->isPassed($parent, $child));
    }
}