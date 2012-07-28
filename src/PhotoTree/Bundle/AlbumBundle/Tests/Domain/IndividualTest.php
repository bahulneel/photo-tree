<?php
namespace PhotoTree\Bundle\AlbumBundle\Domain;

class IndividualTest extends \PHPUnit_Framework_TestCase
{
    public function testAnIndividualIsAnEntity()
    {
        $ind = new Individual;
        $this->assertInstanceOf('PhotoTree\Bundle\AlbumBundle\Domain\Entity', $ind, 'An Individual is an entity');
    }

}