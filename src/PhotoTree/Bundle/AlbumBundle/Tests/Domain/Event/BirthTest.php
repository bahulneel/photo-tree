<?php
namespace PhotoTree\Bundle\AlbumBundle\Domain\Event;

use Mockery as m;

class BirthTest extends \PHPUnit_Framework_TestCase
{
    public function testABirthIsAnEvent()
    {
        $birth = new Birth;
        $this->assertInstanceOf('PhotoTree\Bundle\AlbumBundle\Domain\Event\Event', $birth);
    }

    public function testABirthCanHaveAChild()
    {
        $child = m::mock('PhotoTree\Bundle\AlbumBundle\Domain\Event\Participant\Child');

        $birth = new Birth;

        $child->shouldReceive('setEvent')->once()->with($birth);

        $birth->setChild($child);
        $this->assertSame($child, $birth->getChild(), 'Child is the same');
    }

    /**
     * @depends testABirthCanHaveAChild
     * @expectedException PhotoTree\Bundle\AlbumBundle\Domain\Exception\DomainException
     */
    public function testABirthCanHaveOnlyOneChild()
    {
        $child1 = m::mock('PhotoTree\Bundle\AlbumBundle\Domain\Event\Participant\Child', array(
            'getPerson' => m::mock('PhotoTree\Bundle\AlbumBundle\Domain\Person')
        ));
        $child2 = m::mock('PhotoTree\Bundle\AlbumBundle\Domain\Event\Participant\Child', array(
            'getPerson' => m::mock('PhotoTree\Bundle\AlbumBundle\Domain\Person')
        ));

        $birth = new Birth;

        $child1->shouldReceive('setEvent')->once()->with($birth);

        $birth->setChild($child1);
        $birth->setChild($child2);
    }

    /**
     * @depends testABirthCanHaveAChild
     */
    public function testAChildIsAParticipant()
    {
        $child = m::mock('PhotoTree\Bundle\AlbumBundle\Domain\Event\Participant\Child');

        $birth = new Birth;

        $child->shouldReceive('setEvent')->once()->with($birth);

        $birth->addParticipant($child);
        $this->assertSame($child, $birth->getChild(), 'Child is the same');
    }

    public function testABirthCanHaveAParent()
    {
        $parent = m::mock('PhotoTree\Bundle\AlbumBundle\Domain\Event\Participant\AParent');

        $birth = new Birth;

        $parent->shouldReceive('setEvent')->once()->with($birth);

        $birth->addParent($parent);
        $parents = $birth->getParents();
        $this->assertSame($parent, $parents[0], 'Parent is the same');
    }

    /**
     * @depends testABirthCanHaveAParent
     * @expectedException PhotoTree\Bundle\AlbumBundle\Domain\Exception\DomainException
     */
    public function testBirthParentsMustBeUnique()
    {
        $parent = m::mock('PhotoTree\Bundle\AlbumBundle\Domain\Event\Participant\AParent', array(
            'getPerson' => m::mock('PhotoTree\Bundle\AlbumBundle\Domain\Person')
        ));

        $birth = new Birth;

        $parent->shouldReceive('setEvent')->once()->with($birth);

        $birth->addParent($parent);
        $birth->addParent($parent);
    }

    /**
     * @depends testABirthCanHaveAParent
     */
    public function testABirthCanHaveTwoParents()
    {
        $parent1 = m::mock('PhotoTree\Bundle\AlbumBundle\Domain\Event\Participant\AParent', array(
            'getPerson' => m::mock('PhotoTree\Bundle\AlbumBundle\Domain\Person')
        ));
        $parent2 = m::mock('PhotoTree\Bundle\AlbumBundle\Domain\Event\Participant\AParent', array(
            'getPerson' => m::mock('PhotoTree\Bundle\AlbumBundle\Domain\Person')
        ));

        $birth = new Birth;

        $parent1->shouldReceive('setEvent')->once()->with($birth);
        $parent2->shouldReceive('setEvent')->once()->with($birth);

        $birth->addParent($parent1);
        $birth->addParent($parent2);
        $parents = $birth->getParents();
        $this->assertSame($parent1, $parents[0], 'Parent 1 is the same');
        $this->assertSame($parent2, $parents[1], 'Parent 2 is the same');
    }

    /**
     * @depends testABirthCanHaveTwoParents
     * @expectedException PhotoTree\Bundle\AlbumBundle\Domain\Exception\DomainException
     */
    public function testABirthCanHaveNoMoreThanTwoParents()
    {
        $parent1 = m::mock('PhotoTree\Bundle\AlbumBundle\Domain\Event\Participant\AParent', array(
            'getPerson' => m::mock('PhotoTree\Bundle\AlbumBundle\Domain\Person')
        ));
        $parent2 = m::mock('PhotoTree\Bundle\AlbumBundle\Domain\Event\Participant\AParent', array(
            'getPerson' => m::mock('PhotoTree\Bundle\AlbumBundle\Domain\Person')
        ));
        $parent3 = m::mock('PhotoTree\Bundle\AlbumBundle\Domain\Event\Participant\AParent', array(
            'getPerson' => m::mock('PhotoTree\Bundle\AlbumBundle\Domain\Person')
        ));

        $birth = new Birth;

        $parent1->shouldReceive('setEvent')->once()->with($birth);
        $parent2->shouldReceive('setEvent')->once()->with($birth);

        $birth->addParent($parent1);
        $birth->addParent($parent2);
        $birth->addParent($parent3);

    }

    /**
     * @depends testABirthCanHaveAParent
     */
    public function testABirthParentIsAParticipant()
    {
        $parent = m::mock('PhotoTree\Bundle\AlbumBundle\Domain\Event\Participant\AParent');

        $birth = new Birth;

        $parent->shouldReceive('setEvent')->once()->with($birth);

        $birth->addParticipant($parent);
        $parents = $birth->getParents();
        $this->assertSame($parent, $parents[0], 'Parent is the same');
    }
}
