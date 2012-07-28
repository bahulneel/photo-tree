<?php
namespace PhotoTree\Bundle\AlbumBundle\Domain\Event\Participant;

use Mockery as m;

class ParticipantTest extends \PHPUnit_Framework_TestCase
{
    public function testAPersonCanBeAParticipant()
    {
        $person = m::mock('PhotoTree\Bundle\AlbumBundle\Domain\Person');
        $participant = new Participant();
        $participant->setPerson($person);
        $this->assertSame($person, $participant->getPerson());
    }

    public function testAParticipantHasAnEvent()
    {
        $event = m::mock('PhotoTree\Bundle\AlbumBundle\Domain\Event\Event');
        $participant = new Participant();
        $participant->setEvent($event);
        $this->assertSame($event, $participant->getEvent());
    }
}