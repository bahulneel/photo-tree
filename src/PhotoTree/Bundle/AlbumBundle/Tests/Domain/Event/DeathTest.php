<?php
namespace PhotoTree\Bundle\AlbumBundle\Domain\Event;

use Mockery as m;

class DeathTest extends \PHPUnit_Framework_TestCase
{
    public function testADeathIsAnEvent()
    {
        $death = new Death;
        $this->assertInstanceOf('PhotoTree\Bundle\AlbumBundle\Domain\Event\Event', $death);
    }

    public function testADeathCanHaveADeceased()
    {
        $child = m::mock('PhotoTree\Bundle\AlbumBundle\Domain\Event\Participant\Deceased');

        $death = new Death;

        $child->shouldReceive('setEvent')->once()->with($death);

        $death->setDeceased($child);
        $this->assertSame($child, $death->getDeceased(), 'Deceased is the same');
    }

    /**
     * @depends testADeathCanHaveADeceased
     * @expectedException PhotoTree\Bundle\AlbumBundle\Domain\Exception\DomainException
     */
    public function testADeathCanHaveOnlyOneDeceased()
    {
        $child1 = m::mock('PhotoTree\Bundle\AlbumBundle\Domain\Event\Participant\Deceased', array(
            'getPerson' => m::mock('PhotoTree\Bundle\AlbumBundle\Domain\Person')
        ));
        $child2 = m::mock('PhotoTree\Bundle\AlbumBundle\Domain\Event\Participant\Deceased', array(
            'getPerson' => m::mock('PhotoTree\Bundle\AlbumBundle\Domain\Person')
        ));

        $death = new Death;

        $child1->shouldReceive('setEvent')->once()->with($death);

        $death->setDeceased($child1);
        $death->setDeceased($child2);
    }

    /**
     * @depends testADeathCanHaveADeceased
     */
    public function testADeceasedIsAParticipant()
    {
        $child = m::mock('PhotoTree\Bundle\AlbumBundle\Domain\Event\Participant\Deceased');

        $death = new Death;

        $child->shouldReceive('setEvent')->once()->with($death);

        $death->addParticipant($child);
        $this->assertSame($child, $death->getDeceased(), 'Deceased is the same');
    }
}
