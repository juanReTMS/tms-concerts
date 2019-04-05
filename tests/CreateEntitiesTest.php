<?php

namespace App\Tests;

use Doctrine\ORM\OptimisticLockException as OptimisticLockExceptionAlias;
use Doctrine\ORM\ORMException;
use Exception;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
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

    /**
     * CreateEntitiesTest constructor.
     * @param null $name
     * @param array $data
     * @param string $dataName
     */
    public function __construct($name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $client = static::createClient();
        $this->em = $client->getContainer()->get('doctrine')->getManager();
        $this->doctrine = $client->getContainer()->get('doctrine');
        $this->em->beginTransaction();
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockExceptionAlias
     */
    public function testLocation()
    {
        $location = GenerateUtils::generateLocation();

        self::assertNull($location->getId());

        $this->em->persist($location);
        $this->em->flush();

        self::assertNotNull($location->getId());

    }

    /**
     * @throws ORMException
     * @throws OptimisticLockExceptionAlias
     */
    public function testPerson()
    {
        $person = GenerateUtils::generatePerson();

        self::assertNull($person->getId());

        $this->em->persist($person);
        $this->em->flush();

        self::assertNotNull($person->getId());


    }

    /**
     * @throws Exception
     * @throws ORMException
     * @throws OptimisticLockExceptionAlias
     */
    public function testConcert()
    {
        $location = GenerateUtils::generateLocation();
        $concert = GenerateUtils::generateConcert($location);

        self::assertNull($concert->getId());

        $this->em->persist($location);
        $this->em->persist($concert);
        $this->em->flush();

        self::assertNotNull($concert->getId());
        self::assertEquals($location, $concert->getLocation());

        $person = GenerateUtils::generatePerson();
        $concert->addOrganizer($person);
        self::assertEquals(1, count($concert->getOrganizers()));
        $concert->removeOrganizer($person);
        self::assertEquals(0, count($concert->getOrganizers()));
    }
}
