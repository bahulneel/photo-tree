<?php
namespace PhotoTree\Bundle\AlbumBundle\Domain\Event;

use Mockery as m;

class SourceTest extends \PHPUnit_Framework_TestCase
{
    public function testASourceHasAnEvent()
    {
        $event = m::mock('\PhotoTree\Bundle\AlbumBundle\Domain\Event\Event');
        $source = new Source;

        $source->setEvent($event);

        $this->assertSame($event, $source->getEvent());
    }

    public function testASourceHasADocument()
    {
        $document = m::mock('\PhotoTree\Bundle\AlbumBundle\Domain\Document');
        $source = new Source;

        $source->setDocument($document);

        $this->assertSame($document, $source->getDocument());
    }
}
