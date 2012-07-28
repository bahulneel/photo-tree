<?php
namespace PhotoTree\Bundle\AlbumBundle\Domain;

use Mockery as m;

class PersonTest extends \PHPUnit_Framework_TestCase
{
    public function testAPersonIsAnIndividual()
    {
        $this->assertInstanceOf('PhotoTree\Bundle\AlbumBundle\Domain\Individual', new Person, 'A person is an individual');
    }

    public function testAPersonCanParticipateInAnEvent()
    {
        $role = m::mock(__NAMESPACE__ . '\Event\Participant\Participant');
        $event = m::mock(__NAMESPACE__ . '\Event\Event');

        $person = new Person;

        $role->shouldReceive('setPerson')->once()->with($person);
        $event->shouldReceive('addParticipant')->once()->with($role);

        $person->participate($event, $role);

        $roles = $person->getRoles();
        $this->assertEquals(1, count($roles), 'Number of roles is 1');
        $this->assertSame($role, $roles[0], 'Role is the same');
    }

    /**
     * @depends testAPersonCanParticipateInAnEvent
     */
    public function testAPersonCanParticipateInMoreThanOneEvent()
    {
        $role1 = m::mock(__NAMESPACE__ . '\Event\Participant\Participant');
        $event1 = m::mock(__NAMESPACE__ . '\Event\Event');

        $role2 = m::mock(__NAMESPACE__ . '\Event\Participant\Participant');
        $event2 = m::mock(__NAMESPACE__ . '\Event\Event');

        $person = new Person;

        $role1->shouldReceive('setPerson')->once()->with($person);
        $event1->shouldReceive('addParticipant')->once()->with($role1);

        $role2->shouldReceive('setPerson')->once()->with($person);
        $event2->shouldReceive('addParticipant')->once()->with($role2);

        $person->participate($event1, $role1);
        $person->participate($event2, $role2);

        $roles = $person->getRoles();
        $this->assertEquals(2, count($roles), 'Number of roles is 1');
        $this->assertSame($role1, $roles[0], 'Role 1 is the same');
        $this->assertSame($role2, $roles[1], 'Role 2 is the same');

    }

    public function testAPersonHasAChildThroughBirth()
    {
        $parent = new Person;
        $child = new Person;

        $birth = new Event\Birth;

        $parent->participate($birth, new Event\Participant\AParent());
        $child->participate($birth, new Event\Participant\Child());

        $children = $parent->getChildren();

        $this->assertEquals(1, count($children), 'Parent has one child');
        $this->assertSame($child, $children[0], 'Child is birth child');
    }

    public function testAPersonHasParentsThroughBirth()
    {
        $parent1 = new Person;
        $parent2 = new Person;
        $child = new Person;

        $birth = new Event\Birth;

        $parent1->participate($birth, new Event\Participant\AParent());
        $parent2->participate($birth, new Event\Participant\AParent());
        $child->participate($birth, new Event\Participant\Child());

        $parents = $child->getBirthParents();

        $this->assertEquals(2, count($parents), 'Child has two birth parents');
        $this->assertSame($parent1, $parents[0], 'First parent is same');
        $this->assertSame($parent2, $parents[1], 'Second parent is same');

    }

    /**
     * @expectedException PhotoTree\Bundle\AlbumBundle\Domain\Exception\DomainException
     */
    public function testAPersonCanBeBornOnceOnly()
    {
        $child = new Person;

        $birth1 = new Event\Birth;
        $birth2 = new Event\Birth;

        $child->participate($birth1, new Event\Participant\Child());
        $child->participate($birth2, new Event\Participant\Child());
    }

    public function testAPersonCanDie()
    {
        $deceased = new Person;

        $death = new Event\Death;

        $deceased->participate($death, new Event\Participant\Deceased);

        $expectedDeath = $deceased->getDeath();
        $this->assertSame($death, $expectedDeath, 'Person is died');
        $this->assertSame($deceased, $expectedDeath->getDeceased()->getPerson(), 'Person is dead');
    }

    /**
     * @expectedException PhotoTree\Bundle\AlbumBundle\Domain\Exception\DomainException
     */
    public function testAPersonCanDieOnceOnly()
    {
        $deceased = new Person;

        $death1 = new Event\Death;
        $death2 = new Event\Death;

        $deceased->participate($death1, new Event\Participant\Deceased());
        $deceased->participate($death2, new Event\Participant\Deceased());
    }

    /**
     * @expectedException PhotoTree\Bundle\AlbumBundle\Domain\Exception\DomainException
     */
    public function testAPersonCannotParticipateBeforeBirth()
    {
        $person = new Person;

        $birth = new Event\Birth;
        $birth->setDate(new \DateTime('today'));

        $event = new Event\Event;
        $event->setDate(new \DateTime('yesterday'));

        $person->participate($birth, new Event\Participant\Child);
        $person->participate($event, new Event\Participant\Participant);
    }

    /**
     * @expectedException PhotoTree\Bundle\AlbumBundle\Domain\Exception\DomainException
     */
    public function testAPersonCannotParticipateAfterDeath()
    {
        $person = new Person;

        $death = new Event\Death;
        $death->setDate(new \DateTime('yesterday'));

        $event = new Event\Event;
        $event->setDate(new \DateTime('today'));

        $person->participate($death, new Event\Participant\Deceased);
        $person->participate($event, new Event\Participant\Participant);
    }
}
