<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\ProgramRepository;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/category/', name: 'category_')]
class CategoryController extends AbstractController
{
    #[Route('', name: 'index')]
    public function index(CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();

        return $this->render(

            'category/index.html.twig',

            ['categories' => $categories]

        );
    }

    #[Route('new', name: 'new')]
    public function new(Request $request, CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();

        // Create a new Category Object

        $category = new Category();

        // Create the associated Form

        $form = $this->createForm(CategoryType::class, $category);

        // Get data from HTTP request

        $form->handleRequest($request);

        // Was the form submitted ?

        if ($form->isSubmitted()) {

            $categoryRepository->save($category, true);


            // Redirect to categories list

            return $this->redirectToRoute('category_index');
        }

        // Render the form (best practice)
        return $this->renderForm('category/new.html.twig', [
            'form' => $form,
            'categories' => $categories
        ]);
    }

    #[Route('{categoryName}', name: 'show')]
    public function show(string $categoryName, CategoryRepository $categoryRepository, ProgramRepository $programRepository): Response
    {
        $categories = $categoryRepository->findAll();
        $category = $categoryRepository->findBy(
            ['name' => $categoryName],
        );

        if (!$category) {

            throw $this->createNotFoundException(

                'No category with name : ' . $categoryName . ' found in category\'s table.'

            );
        } else {
            $programs = $programRepository->findBy(
                ['category' => $category],
                ['id' => 'DESC'],
                3
            );
        }

        return $this->render('category/show.html.twig', [
            'programs' => $programs,
            'categories' => $categories
        ]);
    }
}
