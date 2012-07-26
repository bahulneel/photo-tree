<?php
namespace PhotoTree\Bundle\AlbumBundle\Domain;

class IndividualTest extends \PHPUnit_Framework_TestCase
{
    public function testAnIndividualIsAUniqueEntity()
    {
        $ind = new Individual;
        $this->assertObjectHasAttribute('id', $ind, 'Individual has an ID');
    }

}