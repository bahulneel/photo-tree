<?php
namespace PhotoTree\Bundle\AlbumBundle\Domain;

class Entity
{
    protected $id = null;

    public function getId()
    {
        return $this->id;
    }
}
