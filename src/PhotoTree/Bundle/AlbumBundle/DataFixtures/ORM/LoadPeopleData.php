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
    
        $mansukhlal = $this->person($manager, 'Mansukhlal', 'Upadhyaya', '27 Sep 1914');
        $mansukhlal->getBirth()->getChild()->addLineage(new Domain\Lineage\Hindu\Gotra('Kautchchhas'));
        $mansukhlal->setGender(new Domain\Gender\Male);
        $mansukhlal->setProfileImage(new Domain\Document\Image('Bhai_MJU.jpg'));
        $this->died($manager, $mansukhlal, '15 May 1986');
        
        $saraswati = $this->person($manager, 'Saraswati', 'Pandya', '7 Apr 1923');
        $saraswati->setProfileImage(new Domain\Document\Image('saraswati_baa.jpg'));
        $this->died($manager, $saraswati, '23 Jul 2011');
        
        $pramila = $this->person($manager, 'Pramila', 'Shukla', '8 Nov 1939');
        $pramila->setProfileImage(new Domain\Document\Image('Pramila_Shukla01.jpg'));
        $this->died($manager, $pramila, '15 Dec 1974');
        
        $vinod = $this->person($manager, 'Vinod', 'Travadi', '2nd May 1946');
        $vinod->setProfileImage(new Domain\Document\Image('Vinodini_Travadi.jpg'));
        
        $mahesh = $this->person($manager, 'Mahesh', 'Upadhyaya', '4th Nov 1942');
        $mahesh->setGender(new Domain\Gender\Male);
       
        $mahesh->setProfileImage(new Domain\Document\Image('mahesh ecuador.jpg'));
        
        $vimal = $this->person($manager, 'Vimal', 'Upadhyaya', '21st Oct 1967');
        
        $bahul = $this->person($manager, 'Bahul', 'Upadhyaya', '9th June 1978');
        $bahul->setProfileImage(new Domain\Document\Image('bahul.jpg'));
        
        $mayur = $this->person($manager, 'Mayur', 'Upadhyaya', '9th June 1978');        
        $mayur->setGender(new Domain\Gender\Male);
        $mayur->setProfileImage(new Domain\Document\Image('mayur_Upadhyaya.jpg'));
        
        $lucia = $this->person($manager, 'Lucia', 'Ostrihonová', '26 Sep 1980');
        $lucia->setProfileImage(new Domain\Document\Image('Lucia_Upadhyaya.jpg'));

        $carletta = $this->person($manager, 'Carletta', 'Upadhyaya', '16th Oct 2010');
        $carletta->setProfileImage(new Domain\Document\Image('Carletta_Upadhyaya.jpg'));
        
        $this->marry($manager, $mansukhlal, $saraswati, new Domain\Name\Name('Saraswati', 'Upadhyaya'));
        $this->marry($manager, $mahesh, $pramila, new Domain\Name\Name('Pramila', 'Upadhyaya'), '5 Jul 1966');
        
        $this->marry($manager, $mahesh, $vinod, new Domain\Name\Name('Vinod', 'Upadhyaya'), '7th Aug 1976');
        $this->marry($manager, $mayur, $lucia, new Domain\Name\Name('Lucia', 'Upadhyaya'), '28 June 2003');
        
        $this->addParent($mahesh, $mansukhlal);
        $this->addParent($mahesh, $saraswati);
        
        $this->addParent($vimal, $pramila);
        $this->addParent($vimal, $mahesh);
        
        $this->addParent($bahul, $vinod);
        $this->addParent($bahul, $mahesh);
        
        $this->addParent($mayur, $vinod);
        $this->addParent($mayur, $mahesh);
        
        $this->addParent($carletta, $mayur);
        $this->addParent($carletta, $lucia);
        
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
    
    public function died(ObjectManager $manager, Domain\Person $person, $date = null)
    {
        $death = new Domain\Event\Death;
        if ($date) {
            $death->setDate(new \DateTime($date));
        }
        
        $manager->persist($death);
		$manager->flush();
        
        $person->participate($death, new Domain\Event\Participant\Deceased);
    }
    
    public function marry(ObjectManager $manager, Domain\Person $husband, Domain\Person $wife, Domain\Name\Name $wifeName, $date = null)
    {
        $marriage = new Domain\Event\Marriage;
        if ($date) {
            $marriage->setDate(new \DateTime($date));
        }
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
