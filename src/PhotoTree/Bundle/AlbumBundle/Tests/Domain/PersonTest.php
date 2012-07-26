<?php
namespace PhotoTree\Bundle\AlbumBundle\Domain;

class PersonTest extends \PHPUnit_Framework_TestCase
{
    public function testAPersonIsAnIndividual()
    {
        $this->assertInstanceOf('PhotoTree\Bundle\AlbumBundle\Domain\Individual', new Person, 'A person is an individual');
    }

}