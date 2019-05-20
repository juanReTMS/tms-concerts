<?php


namespace App\Tests;


use App\Entity\Concert;
use App\Entity\Location;
use App\Entity\Person;
use DateTime;
use Exception;

class GenerateUtils
{
    /**
     * @param string $institution
     * @param string $building
     * @param string $floor
     * @param string $room
     * @param string $description
     * @param int $seats
     * @return Location
     */
    public static function generateLocation($institution = "TMS", $building = "K", $floor = "Erdgeschoss", $room = "K042", $description = "...", $seats = 42)
    {
        $location = new Location();
        $location->setInstitution($institution);
        $location->setBuilding($building);
        $location->setFloor($floor);
        $location->setRoom($room);
        $location->setDescription($description);
        $location->setSeats($seats);
        return $location;
    }

    /**
     * @param $location
     * @param string $title
     * @param null $start
     * @param null $end
     * @param string $description
     * @param string $program
     * @return Concert
     * @throws Exception
     */
    public static function generateConcert(
        $location,
        $title = "Concert Title",
        $start = null,
        $end = null,
        $description = "Description ...",
        $program = "<h1>Detailed Program... </h1>")
    {
        if (!$start) $start = new DateTime();
        if (!$end) $end = (new DateTime())->modify('+90 minutes');
        $concert = new Concert();
        $concert->setTitle($title);
        $concert->setStart($start);
        $concert->setEnd($end);
        $concert->setPrice(42);
        $concert->setDescription($description);
        $concert->setProgram($program);
        $concert->setLocation($location);
        return $concert;
    }

    /**
     * @param string $firstName
     * @param string $lastName
     * @param string $email
     * @return Person
     */
    public static function generatePerson($firstName = "Alex", $lastName = "Test", $email = 'alex@lex.alex')
    {
        $person = new Person();
        $person->setFirstName($firstName);
        $person->setLastName($lastName);
        $person->setEmail($email);
        return $person;
    }

}