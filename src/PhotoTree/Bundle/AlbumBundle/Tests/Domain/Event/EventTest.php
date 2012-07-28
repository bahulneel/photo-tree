<?php
namespace PhotoTree\Bundle\AlbumBundle\Domain\Event;

use Mockery as m;

class EventTest extends \PHPUnit_Framework_TestCase
{
    public function testAnEventCanHaveAParticipant()
    {
        $participant = m::mock('PhotoTree\Bundle\AlbumBundle\Domain\Event\Participant\Participant');
        $event = new Event;

        $participant->shouldReceive('setEvent')->once()->with($event);

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
        $participant1 = m::mock('PhotoTree\Bundle\AlbumBundle\Domain\Event\Participant\Participant', array(
            'getPerson' => m::mock('PhotoTree\Bundle\AlbumBundle\Domain\Person')
        ));
        $participant2 = m::mock('PhotoTree\Bundle\AlbumBundle\Domain\Event\Participant\Participant', array(
            'getPerson' => m::mock('PhotoTree\Bundle\AlbumBundle\Domain\Person')
        ));

        $event = new Event;

        $participant1->shouldReceive('setEvent')->once()->with($event);
        $participant2->shouldReceive('setEvent')->once()->with($event);

        $event->addParticipant($participant1);
        $event->addParticipant($participant2);

        $participants = $event->getParticipants();
        $this->assertEquals(2, count($participants), 'Participant count is 2');
        $this->assertSame($participant1, $participants[0], 'Participant1 is the same');
        $this->assertSame($participant2, $participants[1], 'Participant2 is the same');
    }

    /**
     * @depends testAnEventCanHaveAParticipant
     * @expectedException PhotoTree\Bundle\AlbumBundle\Domain\Exception\DomainException
     */
    public function testAPersonCannotParticipantMoreThanOnce()
    {

        $person = m::mock('PhotoTree\Bundle\AlbumBundle\Domain\Person');
        $participant1 = m::mock('PhotoTree\Bundle\AlbumBundle\Domain\Event\Participant\Participant', array(
            'getPerson' => $person
        ));

        $participant2 = m::mock('PhotoTree\Bundle\AlbumBundle\Domain\Event\Participant\Participant', array(
            'getPerson' => $person
        ));

        $event = new Event;

        $participant1->shouldReceive('setEvent')->once()->with($event);

        $event->addParticipant($participant1);
        $event->addParticipant($participant2);

    }

    public function testAnEventCanHaveADate()
    {
        $date = m::mock('\DateTime');
        $event = new Event();

        $event->setDate($date);

        $this->assertEquals($event->getDate(), $date, 'Dates are the same');
    }

    /**
     * @depends testAnEventCanHaveADate
     */
    public function testAnEventCanOccurBeforeAnother()
    {
        $date1 = m::mock('\DateTime');
        $event1 = new Event();
        $event1->setDate($date1);

        $date2 = m::mock('\DateTime');
        $event2 = new Event();
        $event2->setDate($date2);

        $diff = m::mock();
        $diff->invert = 1;

        $date1->shouldReceive('diff')->with($date2)->andReturn($diff);
        $this->assertTrue($event2->isBefore($event1), 'Event 2 is before event 1');
    }

    /**
     * @depends testAnEventCanHaveADate
     */
    public function testAnEventCanOccurAfterAnother()
    {
        $date1 = m::mock('\DateTime');
        $event1 = new Event();
        $event1->setDate($date1);

        $date2 = m::mock('\DateTime');
        $event2 = new Event();
        $event2->setDate($date2);

        $diff = m::mock();
        $diff->invert = 0;

        $date1->shouldReceive('diff')->with($date2)->andReturn($diff);
        $this->assertTrue($event2->isAfter($event1), 'Event 2 is after event 1');
    }
}
