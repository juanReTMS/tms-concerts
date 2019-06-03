<?php

namespace App\Controller;

use App\Repository\ConcertRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ClientController extends AbstractController
{
    /**
     * @Route("/client", name="client")
     */
    public function index(ConcertRepository $concertRepository)
    {
        return $this->render('client/index.html.twig', [
            'concerts' => $concertRepository->findAll()
        ]);
    }
}
