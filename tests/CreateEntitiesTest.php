<?php

namespace App\Tests;

use App\Entity\Location;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Doctrine\ORM\EntityManager;

class CreateEntitiesTest extends WebTestCase
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var ManagerRegistry
     */
    private $doctrine;


    public function __construct($name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $client = static::createClient();
        $this->em = $client->getContainer()->get('doctrine')->getManager();
        $this->doctrine = $client->getContainer()->get('doctrine');
    }

    public function testLocation()
    {
        $location = new Location();
        $location->setInstitution("TMS Bad Oldesloe");
        $location->setBuilding("K");
        $location->setFloor("Erdgeschoss");
        $location->setRoom("K042");
        $location->setDescription("Description ...");
        $location->setSeats(42);

        self::assertNull($location->getId());

        $this->em->persist($location);
        $this->em->flush();
        echo $location->getId();
        self::assertNotNull($location->getId());

    }
}
