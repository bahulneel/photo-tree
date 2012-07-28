<?php
namespace PhotoTree\Bundle\AlbumBundle\Domain\Event\Participant;

use Mockery as m;

class SpouseTest extends \PHPUnit_Framework_TestCase
{
    public function testSpouseIsAParicipant()
    {
        $spouse = new Spouse;
        $this->assertInstanceOf('PhotoTree\Bundle\AlbumBundle\Domain\Event\Participant\Participant', $spouse);
    }

    public function testSpouseCanHaveAName()
    {
        $name = m::mock('PhotoTree\Bundle\AlbumBundle\Domain\Name\Name');
        $spouse = new Spouse;
        $name->shouldReceive('setParticipant')->once()->with($spouse);
        $spouse->setName($name);
        $this->assertSame($name, $spouse->getName());
    }
}