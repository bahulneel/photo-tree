<?php
namespace PhotoTree\Bundle\AlbumBundle\Domain;

class EntityTest extends \PHPUnit_Framework_TestCase
{
    public function testAnEntityHasAnId()
    {
        $entity = new Entity;
        $this->assertObjectHasAttribute('id', $entity, 'Entity has an ID');
    }

}