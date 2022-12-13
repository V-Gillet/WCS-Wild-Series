<?php

namespace App\Controller;

use App\Entity\Actor;
use App\Repository\ActorRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


#[Route('/actor/', name: 'actor_')]
class ActorController extends AbstractController
{
    #[Route('', name: 'index')]
    public function index(ActorRepository $actorRepository): Response
    {

        $actors = $actorRepository->findAll();
        return $this->render('actor/index.html.twig', [
            'actors' => $actors,
        ]);
    }

    #[Route('{actor}', methods: ['GET'], name: 'show')]
    public function show(Actor $actor): Response
    {

        if (!$actor) {
            throw $this->createNotFoundException(

                'No program with id : ' . ' found in program\'s table.'

            );
        };

        return $this->render('actor/show.html.twig', [

            'actor' => $actor,
        ]);
    }
}
