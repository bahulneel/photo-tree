<?php
namespace PhotoTree\Bundle\AlbumBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use PhotoTree\Bundle\AlbumBundle\Domain;

class LoadPeopleData implements FixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $bahul = $this->person($manager, 'Bahul', 'Upadhyaya', '9th June 1978');
        $mayur = $this->person($manager, 'Mayur', 'Upadhyaya', '9th June 1978');
        $mayur->setGender(new Domain\Gender\Male);
        
        $vinod = $this->person($manager, 'Vinod', 'Travadi', '2nd May 1946');
        $mahesh = $this->person($manager, 'Mahesh', 'Upadhyaya', '4th Nov 1942');
        $mahesh->setGender(new Domain\Gender\Male);
        $mahesh->getBirth()->getChild()->addLineage(new Domain\Lineage\Hindu\Gotra('Kautchchhas'));
        
        $this->addParent($bahul, $vinod);
        $this->addParent($bahul, $mahesh);
        
        $this->addParent($mayur, $vinod);
        $this->addParent($mayur, $mahesh);
        
        $this->marry($manager, $mahesh, $vinod, new Domain\Name\Name('Vinod', 'Upadhyaya'), '7th Aug 1976');
        
        $carletta = $this->person($manager, 'Carletta', 'Upadhyaya', '16th Oct 2010');
        $this->addParent($carletta, $mayur);
        
        $manager->flush();
    }
    
    public function person(ObjectManager $manager, $first, $last, $dob)
    {
        $person = new Domain\Person;
		$manager->persist($person);
		$manager->flush();
		
		$personBirth = new Domain\Event\Birth;
		$personBirth->setDate(new \DateTime($dob));
		$manager->persist($personBirth);
        $manager->flush();

        $person->participate($personBirth, new Domain\Event\Participant\Child);
		
		$personBirth->getChild()->setName(new Domain\Name\Name($first, $last));
        $manager->flush();
        
        return $person;
    }
    
    public function marry(ObjectManager $manager, Domain\Person $husband, Domain\Person $wife, Domain\Name\Name $wifeName, $date)
    {
        $marriage = new Domain\Event\Marriage;
        $marriage->setDate(new \DateTime($date));
        $manager->persist($marriage);
        $manager->flush();
        
        $husband->participate($marriage, new Domain\Event\Participant\Spouse);
        
        $wifeSpouse = new Domain\Event\Participant\Spouse;
        $wifeSpouse->setName($wifeName);
        $wife->participate($marriage, $wifeSpouse);
        
    }
    
    public function addParent(Domain\Person $child, Domain\Person $parent)
    {
        $parent->participate($child->getBirth(), new Domain\Event\Participant\AParent);
    }
}
