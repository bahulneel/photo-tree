<?php
namespace PhotoTree\Bundle\AlbumBundle\Domain\Event;

use Mockery as m;

class MarriageTest extends \PHPUnit_Framework_TestCase
{
    public function testAMarriageIsAnEvent()
    {
        $marriage = new Marriage;
        $this->assertInstanceOf('PhotoTree\Bundle\AlbumBundle\Domain\Event\Event', $marriage);
    }

    public function testAMarriageCanHaveASpouse()
    {
        $spouse = m::mock('PhotoTree\Bundle\AlbumBundle\Domain\Event\Participant\Spouse');

        $marriage = new Marriage;

        $spouse->shouldReceive('setEvent')->once()->with($marriage);

        $marriage->addSpouse($spouse);
        $spouses = $marriage->getSpouses();
        $this->assertSame($spouse, $spouses[0], 'Spouse is the same');
    }

    /**
     * @depends testAMarriageCanHaveASpouse
     * @expectedException PhotoTree\Bundle\AlbumBundle\Domain\Exception\DomainException
     */
    public function testMarriageSpousesMustBeUnique()
    {
        $spouse = m::mock('PhotoTree\Bundle\AlbumBundle\Domain\Event\Participant\Spouse', array(
            'getPerson' => m::mock('PhotoTree\Bundle\AlbumBundle\Domain\Person')
        ));

        $marriage = new Marriage;

        $spouse->shouldReceive('setEvent')->once()->with($marriage);

        $marriage->addSpouse($spouse);
        $marriage->addSpouse($spouse);
    }

    /**
     * @depends testAMarriageCanHaveASpouse
     */
    public function testAMarriageCanHaveTwoSpouses()
    {
        $spouse1 = m::mock('PhotoTree\Bundle\AlbumBundle\Domain\Event\Participant\Spouse', array(
            'getPerson' => m::mock('PhotoTree\Bundle\AlbumBundle\Domain\Person')
        ));
        $spouse2 = m::mock('PhotoTree\Bundle\AlbumBundle\Domain\Event\Participant\Spouse', array(
            'getPerson' => m::mock('PhotoTree\Bundle\AlbumBundle\Domain\Person')
        ));

        $marriage = new Marriage;

        $spouse1->shouldReceive('setEvent')->once()->with($marriage);
        $spouse2->shouldReceive('setEvent')->once()->with($marriage);

        $marriage->addSpouse($spouse1);
        $marriage->addSpouse($spouse2);
        $spouses = $marriage->getSpouses();
        $this->assertSame($spouse1, $spouses[0], 'Spouse 1 is the same');
        $this->assertSame($spouse2, $spouses[1], 'Spouse 2 is the same');
    }

    /**
     * @depends testAMarriageCanHaveTwoSpouses
     * @expectedException PhotoTree\Bundle\AlbumBundle\Domain\Exception\DomainException
     */
    public function testAMarriageCanHaveNoMoreThanTwoSpouses()
    {
        $spouse1 = m::mock('PhotoTree\Bundle\AlbumBundle\Domain\Event\Participant\Spouse', array(
            'getPerson' => m::mock('PhotoTree\Bundle\AlbumBundle\Domain\Person')
        ));
        $spouse2 = m::mock('PhotoTree\Bundle\AlbumBundle\Domain\Event\Participant\Spouse', array(
            'getPerson' => m::mock('PhotoTree\Bundle\AlbumBundle\Domain\Person')
        ));
        $spouse3 = m::mock('PhotoTree\Bundle\AlbumBundle\Domain\Event\Participant\Spouse', array(
            'getPerson' => m::mock('PhotoTree\Bundle\AlbumBundle\Domain\Person')
        ));

        $marriage = new Marriage;

        $spouse1->shouldReceive('setEvent')->once()->with($marriage);
        $spouse2->shouldReceive('setEvent')->once()->with($marriage);

        $marriage->addSpouse($spouse1);
        $marriage->addSpouse($spouse2);
        $marriage->addSpouse($spouse3);

    }

    /**
     * @depends testAMarriageCanHaveASpouse
     */
    public function testAMarriageSpouseIsAParticipant()
    {
        $spouse = m::mock('PhotoTree\Bundle\AlbumBundle\Domain\Event\Participant\Spouse');

        $marriage = new Marriage;

        $spouse->shouldReceive('setEvent')->once()->with($marriage);

        $marriage->addParticipant($spouse);
        $spouses = $marriage->getSpouses();
        $this->assertSame($spouse, $spouses[0], 'Spouse is the same');
    }
}
