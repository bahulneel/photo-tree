<?php
namespace PhotoTree\Bundle\AlbumBundle\Domain\Event\Participant;

use Mockery as m;

class AParentTest extends \PHPUnit_Framework_TestCase
{
    public function testAParentIsAParicipant()
    {
        $parent = new AParent;
        $this->assertInstanceOf('PhotoTree\Bundle\AlbumBundle\Domain\Event\Participant\Participant', $parent);
    }

    public function testAMaleParentIsAFather()
    {
        $parent = new AParent;
        $male = m::mock('\PhotoTree\Bundle\AlbumBundle\Domain\Gender\Male');
        $person = m::mock('\PhotoTree\Bundle\AlbumBundle\Domain\Person',array(
            'getGender' => $male
        ));

        $parent->setPerson($person);

        $this->assertTrue($parent->isFather());
    }

    public function testAFemaleParentIsNotAFather()
    {
        $parent = new AParent;
        $female = m::mock('\PhotoTree\Bundle\AlbumBundle\Domain\Gender\Female');
        $person = m::mock('\PhotoTree\Bundle\AlbumBundle\Domain\Person',array(
            'getGender' => $female
        ));

        $parent->setPerson($person);

        $this->assertFalse($parent->isFather());
    }

    public function testAMaleParentIsNotAMother()
    {
        $parent = new AParent;
        $male = m::mock('\PhotoTree\Bundle\AlbumBundle\Domain\Gender\Male');
        $person = m::mock('\PhotoTree\Bundle\AlbumBundle\Domain\Person',array(
            'getGender' => $male
        ));

        $parent->setPerson($person);

        $this->assertFalse($parent->isMother());
    }

    public function testAFemaleParentIsAMother()
    {
        $parent = new AParent;
        $female = m::mock('\PhotoTree\Bundle\AlbumBundle\Domain\Gender\Female');
        $person = m::mock('\PhotoTree\Bundle\AlbumBundle\Domain\Person',array(
            'getGender' => $female
        ));

        $parent->setPerson($person);

        $this->assertTrue($parent->isMother());
    }
}