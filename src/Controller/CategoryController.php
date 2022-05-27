<?php
// src/Controller/ProgramController.php
namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\ProgramRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/category', name: 'category_')]
class CategoryController extends AbstractController
{
  #[Route('/', name: 'index')]
  public function index(CategoryRepository $categoryRepository): Response
  {
    $categories = $categoryRepository->findAll();
    return $this->render('category/index.html.twig', [
      'categories' => $categories,
    ]);
  }

  #[Route('/{categoryName}', methods: ['GET'], name: 'show')]
  public function show(string $categoryName, CategoryRepository $categoryRepository, ProgramRepository $programRepository): Response
  {
    $category = $categoryRepository->findByName($categoryName);
    $categoryId = $category[0]->getId();
    $programs = $programRepository->findBy(['category' => $categoryId], null, 3);
    // if (!$programs) {
    //   throw $this->createNotFoundException(
    //     'No programs with category name : ' . $categoryName . ' found in program\'s table.'
    //   );
    // }
    return $this->render('category/show.html.twig', [
      'category' => $categoryName,
      'programs' => $programs,
    ]);
  }
}
