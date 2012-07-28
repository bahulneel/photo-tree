<?php
namespace PhotoTree\Bundle\AlbumBundle\Domain\Event;

use PhotoTree\Bundle\AlbumBundle\Domain\Document;

class Source
{
    /**
     *
     * @var Event
     */
    private $event;

    /**
     *
     * @var Document
     */
    private $document;

    public function setEvent(Event $event)
    {
        $this->event = $event;
    }

    public function getEvent()
    {
        return $this->event;
    }

    public function setDocument(Document $document)
    {
        $this->document = $document;
    }

    public function getDocument()
    {
        return $this->document;
    }
}

