<?php
namespace PhotoTree\Bundle\AlbumBundle\Domain\Gender;

class Male extends AbstractGender
{
    public function getValue()
    {
        return 'M';
    }
}