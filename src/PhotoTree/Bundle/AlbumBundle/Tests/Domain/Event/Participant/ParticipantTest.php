<?php
namespace PhotoTree\Bundle\AlbumBundle\Domain\Event\Participant;

use Mockery as m;

class ParticipantTest extends \PHPUnit_Framework_TestCase
{
    public function testAPersonCanBeAParticipant()
    {
        $person = m::mock('PhotoTree\Bundle\AlbumBundle\Domain\Person');
        $participant = new Participant($person);
        $this->assertSame($person, $participant->getPerson());
    }

    public function testAParticipantCanHaveARole()
    {
        $person = m::mock('PhotoTree\Bundle\AlbumBundle\Domain\Person');
        $participant = new Participant($person);

        $role = m::mock('PhotoTree\Bundle\AlbumBundle\Domain\Event\Role');
        $participant->setRole($role);

        $this->assertSame($role, $participant->getRole());
    }
}