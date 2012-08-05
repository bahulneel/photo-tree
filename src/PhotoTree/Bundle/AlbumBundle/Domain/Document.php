<?php
namespace PhotoTree\Bundle\AlbumBundle\Domain;

class Document extends Entity
{
    private $path;
    
    public function __construct($path)
    {
        $this->path = $path;
    }
    
    public function getPath()
    {
        return $this->path;
    }
}
