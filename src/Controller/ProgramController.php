<?php

namespace App\Controller;

use App\Entity\Season;
use App\Entity\Episode;
use App\Entity\Program;
use App\Form\ProgramType;
use App\Repository\SeasonRepository;
use App\Repository\EpisodeRepository;
use App\Repository\ProgramRepository;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/program/', name: 'program_')]
class ProgramController extends AbstractController
{
    #[Route('', name: 'index')]
    public function index(ProgramRepository $programRepository, CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();

        $programs = $programRepository->findAll();

        return $this->render(

            'program/index.html.twig',

            [
                'programs' => $programs,
                'categories' => $categories,
            ],

        );
    }

    #[Route('new', name: 'new')]
    public function new(Request $request, ProgramRepository $programRepository, CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();

        // Create a new Category Object

        $program = new Program();

        // Create the associated Form

        $form = $this->createForm(ProgramType::class, $program);

        // Get data from HTTP request

        $form->handleRequest($request);

        // Was the form submitted ?

        if ($form->isSubmitted() && $form->isValid()) {

            $programRepository->save($program, true);

            $this->addFlash('success', 'The new program has been created');

            // Redirect to categories list

            return $this->redirectToRoute('program_index');
        }

        // Render the form (best practice)
        return $this->renderForm('program/new.html.twig', [
            'form' => $form,
            'categories' => $categories
        ]);
    }

    #[Route('show/{program}', methods: ['GET'], name: 'show')]
    public function show(Program $program, CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();

        if (!$program) {
            throw $this->createNotFoundException(

                'No program with id : ' . ' found in program\'s table.'

            );
        };

        return $this->render('program/show.html.twig', [

            'program' => $program,
            'categories' => $categories,

        ]);
    }

    #[Route('{program}/season/{season}', methods: ['GET'], name: 'season_show')]
    public function showSeason(Season $season, CategoryRepository $categoryRepository, EpisodeRepository $episodeRepository): Response
    {
        $categories = $categoryRepository->findAll();

        $episodes = $episodeRepository->findBy(
            ['season' => $season],
        );

        return $this->render('program/season_show.html.twig', [

            'categories' => $categories,
            'season' => $season,
            'episodes' => $episodes,

        ]);
    }

    #[Route('{program}/season/{season}/episode/{episode}', methods: ['GET'], name: 'episode_show')]
    public function showEpisode(Episode $episode, CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();

        return $this->render('program/episode_show.html.twig', [

            'categories' => $categories,
            'episode' => $episode,
        ]);
    }
}
