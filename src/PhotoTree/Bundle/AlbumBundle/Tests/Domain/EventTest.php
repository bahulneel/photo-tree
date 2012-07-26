<?php
namespace PhotoTree\Bundle\AlbumBundle\Domain;

use Mockery as m;

class EventTest extends \PHPUnit_Framework_TestCase
{
    public function testAnEventCanHaveAParticipant()
    {
        $participant = m::mock('PhotoTree\Bundle\AlbumBundle\Domain\Event\Participant');

        $event = new Event;

        $this->assertEquals(0, count($event->getParticipants()), 'Events start with no participants');

        $event->addParticipant($participant);

        $participants = $event->getParticipants();
        $this->assertEquals(1, count($participants), 'Participant count increases');
        $this->assertSame($participant, $participants[0], 'Participant is the same');
    }

    /**
     * @depends testAnEventCanHaveAParticipant
     */
    public function testAnEventCanHaveMoreThanOneParticipant()
    {
        $participant1 = m::mock('PhotoTree\Bundle\AlbumBundle\Domain\Event\Participant', array(
            'getPerson' => m::mock('PhotoTree\Bundle\AlbumBundle\Domain\Person')
        ));
        $participant2 = m::mock('PhotoTree\Bundle\AlbumBundle\Domain\Event\Participant', array(
            'getPerson' => m::mock('PhotoTree\Bundle\AlbumBundle\Domain\Person')
        ));

        $event = new Event;
        $event->addParticipant($participant1);
        $event->addParticipant($participant2);

        $participants = $event->getParticipants();
        $this->assertEquals(2, count($participants), 'Participant count is 2');
        $this->assertSame($participant1, $participants[0], 'Participant1 is the same');
        $this->assertSame($participant2, $participants[1], 'Participant2 is the same');
    }

    /**
     * @depends testAnEventCanHaveAParticipant
     */
    public function testAPersonCannotParticipantMoreThanOnce()
    {

        $person = m::mock('PhotoTree\Bundle\AlbumBundle\Domain\Person');
        $participant1 = m::mock('PhotoTree\Bundle\AlbumBundle\Domain\Event\Participant', array(
            'getPerson' => $person
        ));

        $participant2 = m::mock('PhotoTree\Bundle\AlbumBundle\Domain\Event\Participant', array(
            'getPerson' => $person
        ));

        $event = new Event;

        $event->addParticipant($participant1);
        $event->addParticipant($participant2);

        $participants = $event->getParticipants();
        $this->assertEquals(1, count($participants), 'Participant is 1');
        $this->assertSame($person, $participants[0]->getPerson(), 'Participant is the same');
    }
}
