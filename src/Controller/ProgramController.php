<?php

namespace App\Controller;

use App\Entity\Season;
use App\Entity\Episode;
use App\Entity\Program;
use App\Form\ProgramType;
use App\Service\ProgramDuration;
use Symfony\Component\Mime\Email;
use App\Repository\SeasonRepository;
use App\Repository\EpisodeRepository;
use App\Repository\ProgramRepository;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
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
    public function new(Request $request, ProgramRepository $programRepository, CategoryRepository $categoryRepository, SluggerInterface $slugger, MailerInterface $mailer): Response
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

            $slug = $slugger->slug($program->getTitle());

            $program->setSlug($slug);

            $programRepository->save($program, true);

            $this->addFlash('success', 'The new program has been created');

            $email = (new Email())

                ->from($this->getParameter('mailer_from'))

                ->to('your_email@example.com')

                ->subject('Une nouvelle série vient d\'être publiée !')

                ->html('<p>Une nouvelle série vient d\'être publiée sur Wild Séries !</p>');

            $mailer->send($email);

            // Redirect to categories list

            return $this->redirectToRoute('program_index');
        }

        // Render the form (best practice)
        return $this->renderForm('program/new.html.twig', [
            'form' => $form,
            'categories' => $categories
        ]);
    }

    #[Route('show/{slug}', methods: ['GET'], name: 'show')]
    public function show(Program $program, CategoryRepository $categoryRepository, ProgramDuration $programDuration): Response
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
            'programDuration' => $programDuration->calculate($program)

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

    #[Route('{program}/season/{season}/episode/{slug}', methods: ['GET'], name: 'episode_show')]
    public function showEpisode(Episode $episode, CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();

        return $this->render('program/episode_show.html.twig', [

            'categories' => $categories,
            'episode' => $episode,
        ]);
    }

    #[Route('{slug}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Program $program, ProgramRepository $programRepository, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(ProgramType::class, $program);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $slug = $slugger->slug($program->getTitle());

            $program->setSlug($slug);

            $programRepository->save($program, true);

            $this->addFlash('success', 'The program has been modified');

            return $this->redirectToRoute('program_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('program/edit.html.twig', [
            'program' => $program,
            'form' => $form,
        ]);
    }

    #[Route('{slug}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, Program $program, ProgramRepository $programRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $program->getId(), $request->request->get('_token'))) {
            $programRepository->remove($program, true);
            $this->addFlash('danger', 'The program has been deleted');
        }

        return $this->redirectToRoute('program_index', [], Response::HTTP_SEE_OTHER);
    }
}
