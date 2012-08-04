<?php
namespace PhotoTree\Bundle\AlbumBundle\App;

use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

class Timeline implements \Countable
{
    private $events = array();
    
    public function addRoles(Collection $roles)
    {
        foreach ($roles as $role) {
            $this->events[] = new Timeline\Event($role);
        }
    }
    
    public function count()
    {
        return count($this->events);
    }
    
    public function getEvents()
    {
        $events = $this->events;
        usort($events, function($a, $b) {
            if ($a->getDate() > $b->getDate()) {
                return -1;
            }
            if ($a->getDate() < $b->getDate()) {
                return 1;
            }
            return 0;
        });
        return $events;
    }
}
