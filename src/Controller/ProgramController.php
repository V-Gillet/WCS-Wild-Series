<?php

namespace App\Controller;

use App\Repository\ProgramRepository;
use App\Repository\CategoryRepository;
use App\Repository\SeasonRepository;
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

    #[Route('show/{id<\d+>}', methods: ['GET'], name: 'show')]
    public function show(int $id, ProgramRepository $programRepository, CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();
        $program = $programRepository->findOneBy(['id' => $id]);


        if (!$program) {
            throw $this->createNotFoundException(

                'No program with id : ' . $id . ' found in program\'s table.'

            );
        };

        return $this->render('program/show.html.twig', [

            'program' => $program,
            'categories' => $categories,

        ]);
    }

    #[Route('program/{programId}/season/{seasonId}', methods: ['GET'], name: 'season_show')]
    public function showSeason(int $programId, int $seasonId, ProgramRepository $programRepository, CategoryRepository $categoryRepository, SeasonRepository $seasonRepository): Response
    {
        $categories = $categoryRepository->findAll();

        $season = $seasonRepository->findOneBy(
            ['id' => $seasonId],
        );
        //var_dump($season);
        //exit();

        return $this->render('program/season_show.html.twig', [

            'categories' => $categories,
            'season' => $season,

        ]);
    }
}
