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

    public function testAPersonCanHaveASpouse()
    {
        $person1 = new Person;

        $person2 = new Person;

        $marriage = new Event\Marriage;

        $person1->participate($marriage, new Event\Participant\Spouse());
        $person2->participate($marriage, new Event\Participant\Spouse());

        $this->assertSame($person2, $person1->getSpouse());
        $this->assertSame($person1, $person2->getSpouse());
    }

    public function testAPersonCanHaveMoreThanOneSpouse()
    {
        $person1 = new Person;

        $person2 = new Person;

        $person3 = new Person;

        $marriage1 = new Event\Marriage;
        $marriage2 = new Event\Marriage;

        $person1->participate($marriage1, new Event\Participant\Spouse());
        $person2->participate($marriage1, new Event\Participant\Spouse());

        $person1->participate($marriage2, new Event\Participant\Spouse());
        $person3->participate($marriage2, new Event\Participant\Spouse());

        $spouses = $person1->getSpouses();

        $this->assertSame($person2, $spouses[0], 'Person 2 is the first spouse');
        $this->assertSame($person3, $spouses[1], 'Person 3 is the second spouse');
    }

    public function testAPersonsSpouseIsTheMostRecentSpouse()
    {
        $person1 = new Person;

        $person2 = new Person;

        $person3 = new Person;

        $marriage1 = new Event\Marriage;
        $marriage1->setDate(new \DateTime('yesterday'));

        $marriage2 = new Event\Marriage;
        $marriage2->setDate(new \DateTime('today'));

        $person1->participate($marriage1, new Event\Participant\Spouse());
        $person2->participate($marriage1, new Event\Participant\Spouse());

        $person1->participate($marriage2, new Event\Participant\Spouse());
        $person3->participate($marriage2, new Event\Participant\Spouse());

        $this->assertSame($person3, $person1->getSpouse(), 'Person 3 is the current spouse');
    }

    public function testAPersonHasNoSpouseIfTheSpouseIsDead()
    {
        $person1 = new Person;

        $person2 = new Person;

        $marriage = new Event\Marriage;

        $person1->participate($marriage, new Event\Participant\Spouse());
        $person2->participate($marriage, new Event\Participant\Spouse());

        $death = new Event\Death();
        $person2->participate($death, new Event\Participant\Deceased);

        $this->assertNull($person1->getSpouse());
    }

    public function testAPersonCanHaveABirthName()
    {
        $person = new Person();

        $birth = new Event\Birth();

        $person->participate($birth, new Event\Participant\Child());

        $name = m::mock('PhotoTree\Bundle\AlbumBundle\Domain\Name\Name');
        $person->getBirth()->getChild()->setName($name);

        $this->assertSame($name, $person->getBirthName());
    }

    public function testAPersonCanHaveAMarriedName()
    {
        $person = new Person();

        $marriage = new Event\Marriage;

        $name = m::mock('PhotoTree\Bundle\AlbumBundle\Domain\Name\Name');

        $spouse = new Event\Participant\Spouse;
        $spouse->setName($name);
        $person->participate($marriage, $spouse);

        $this->assertSame($name, $person->getMarriedName());
    }

    public function testCurrentNameIsBirthNameWithNoOtherChanges()
    {
        $person = new Person();

        $birth = new Event\Birth();

        $person->participate($birth, new Event\Participant\Child());

        $name = m::mock('PhotoTree\Bundle\AlbumBundle\Domain\Name\Name');
        $person->getBirth()->getChild()->setName($name);

        $this->assertSame($name, $person->getCurrentName());
    }

    public function testCurrentNameIsMarriedNameIfNameChanged()
    {
        $person = new Person();

        $birth = new Event\Birth();

        $person->participate($birth, new Event\Participant\Child());

        $name = m::mock('PhotoTree\Bundle\AlbumBundle\Domain\Name\Name');
        $person->getBirth()->getChild()->setName($name);

        $marriage = new Event\Marriage;

        $mName = m::mock('PhotoTree\Bundle\AlbumBundle\Domain\Name\Name');

        $spouse = new Event\Participant\Spouse;
        $spouse->setName($mName);

        $person->participate($marriage, $spouse);
        $this->assertSame($mName, $person->getCurrentName());
    }

    public function testAPersonCanHaveGender()
    {
        $gender = m::mock('PhotoTree\Bundle\AlbumBundle\Domain\Gender\AbstractGender');

        $person = new Person();
        $person->setGender($gender);

        $this->assertSame($gender, $person->getGender());
    }
}
