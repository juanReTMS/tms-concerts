<?php

namespace App\DataFixtures;

use App\Entity\Concert;
use App\Entity\Location;
use App\Entity\Person;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Constraints\Date;

class AppFixtures extends Fixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }


    /**
     * @param ObjectManager $manager
     * @throws \Exception
     */
    public function load(ObjectManager $manager)
    {
        $person1 = new Person();
        $person1->setFirstName("Alex");
        $person1->setLastName("Test");
        $person1->setEmail("alex@alex.alex");
        $person1->setTelephone('04242 12345');
        $person1->setPassword($this->encoder->encodePassword($person1, "admin"));
        $person1->setConfirmed(true);

        $person2 = new Person();
        $person2->setFirstName("Darian");
        $person2->setLastName("Test");
        $person2->setEmail("darian@darian.darian");
        $person2->setPassword($this->encoder->encodePassword($person2, "admin"));
        $person2->setConfirmed(true);

        $person3 = new Person();
        $person3->setFirstName("Ming");
        $person3->setLastName("Test");
        $person3->setEmail("main@ming.ming");
        $person3->setPassword($this->encoder->encodePassword($person3, "admin"));

        $location1 = new Location();
        $location1->setInstitution("TMS");
        $location1->setBuilding("A");
        $location1->setFloor("2. Stock");
        $location1->setRoom("A242");
        $location1->setSeats(100);
        $location1->setDescription("Description of location 1");
        $location1->addContact($person1);
        $location2 = new Location();
        $location2->setInstitution("TMS");
        $location2->setBuilding("H");
        $location2->setFloor("Erdgeschoss");
        $location2->setRoom("H003");
        $location2->setSeats(100);
        $location2->setDescription("Description of location 2");
        $location2->addContact($person2);
        $location3 = new Location();
        $location3->setInstitution("Stormanhalle");
        $location3->setBuilding("Stormarnhalle");
        $location3->setSeats(200);
        $location3->setDescription("Description of location 3");

        $baseDate = new DateTime(date("Y-m-d H:00:00", (new DateTime())->getTimestamp()));#Full hour of current time

        $concert1 = new Concert();
        $concert1->setTitle("Frühingskonzert");
        $concert1->setStart($baseDate);
        $concert1->setEnd((clone $baseDate)->modify("+90 minutes"));
        $concert1->setDescription("Desciption ...");
        $concert1->setProgram("<ul><li>First Part</li><li>Second Part</li></ul>");
        $concert1->setPrice(42);
        $concert1->setLocation($location1);
        $concert1->addOrganizer($person1);
        $concert1->addOrganizer($person2);

        $baseDate = (clone $baseDate)->modify("+60 minutes");
        #Clone object and modify time, without cloning the value would change in previous entities too

        $concert2 = new Concert();
        $concert2->setTitle("Sommerkonzert");
        $concert2->setStart($baseDate);
        $concert2->setEnd((clone $baseDate)->modify("+60 minutes"));
        $concert2->setDescription("Desciption ...");
        $concert2->setLocation($location3);
        $concert2->addOrganizer($person3);

        $manager->persist($person1);
        $manager->persist($person2);
        $manager->persist($person3);
        $manager->persist($location1);
        $manager->persist($location2);
        $manager->persist($location3);
        $manager->persist($concert1);
        $manager->persist($concert2);


        $manager->flush();
    }
}
