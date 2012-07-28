<?php
namespace PhotoTree\Bundle\AlbumBundle\Domain\Lineage;

use Mockery as m;

class MaternalTest extends \PHPUnit_Framework_TestCase
{
    public function testAnMaternalIsALineage()
    {
        $lineage = new Maternal;
        $this->assertInstanceOf('PhotoTree\Bundle\AlbumBundle\Domain\Lineage\Lineage', $lineage, 'An Maternal is an entity');
    }

    public function testMaternalIsPassedFromMotherToChildren()
    {
        $parent = m::mock('PhotoTree\Bundle\AlbumBundle\Domain\Event\Participant\AParent', array(
            'isMother' => true
        ));
        $child = m::mock('PhotoTree\Bundle\AlbumBundle\Domain\Event\Participant\Child');

        $lineage = new Maternal;

        $this->assertTrue($lineage->isPassed($parent, $child));
    }

    public function testMaternalIsPassedNotFromMotherToChildren()
    {
        $parent = m::mock('PhotoTree\Bundle\AlbumBundle\Domain\Event\Participant\AParent', array(
            'isMother' => false
        ));
        $child = m::mock('PhotoTree\Bundle\AlbumBundle\Domain\Event\Participant\Child');

        $lineage = new Maternal;

        $this->assertFalse($lineage->isPassed($parent, $child));
    }
}