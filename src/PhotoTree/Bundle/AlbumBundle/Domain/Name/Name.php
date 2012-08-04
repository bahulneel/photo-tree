<?php
namespace PhotoTree\Bundle\AlbumBundle\Domain\Name;

use PhotoTree\Bundle\AlbumBundle\Domain\Event\Participant\Participant;
use PhotoTree\Bundle\AlbumBundle\Domain\Entity;

class Name extends Entity
{
    /**
     *
     * @var Participant
     */
    private $participant;

	private $firstName;
	
	private $lastName;
	
	public function __construct($first, $last)
	{
		$this->setFirstName($first);
		$this->setLastName($last);
	}
	
    public function setParticipant(Participant $participant)
    {
        $this->participant = $participant;
    }
	
    public function getParticipant()
    {
        return $this->participant;
    }
    
	public function setFirstName($name)
	{
		$this->firstName = $name;
	}
	
	public function setLastName($name)
	{
		$this->lastName = $name;
	}
    
    public function getFirstName()
	{
		return $this->firstName;
	}
	
	public function getLastName()
	{
		return $this->lastName;
	}
}
