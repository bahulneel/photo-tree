<?php
namespace PhotoTree\Bundle\AlbumBundle\Domain\Gender;

class Female extends AbstractGender
{
    public function getValue()
    {
        return 'F';
    }
}