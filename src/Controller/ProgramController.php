<?php
// src/Controller/ProgramController.php
namespace App\Controller;

use App\Entity\Program;
use App\Form\ProgramType;
use App\Repository\EpisodeRepository;
use App\Repository\ProgramRepository;
use App\Repository\SeasonRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/program', name: 'program_')]
class ProgramController extends AbstractController
{
  #[Route('/', name: 'index')]
  public function index(ProgramRepository $programRepository): Response
  {
    $programs = $programRepository->findAll();
    return $this->render('program/index.html.twig', [
      'programs' => $programs,
    ]);
  }

  #[Route('/{id<\d+>}', methods: ['GET'], name: 'show')]
  public function show(int $id, ProgramRepository $programRepository, SeasonRepository $seasonRepository): Response
  {
    $program = $programRepository->findOneById($id);
    if (!$program) {
      throw $this->createNotFoundException(
        'No program with id : ' . $id . ' found in program\'s table.'
      );
    }
    $seasons = $seasonRepository->findByProgram($program);
    return $this->render('program/show.html.twig', [
      'program' => $program,
      'seasons' => $seasons,
    ]);
  }

  #[Route('/{programId<\d+>}/season/{seasonId<\d+>}', methods: ['GET'], name: 'season_show')]
  public function showSeason(int $programId, int $seasonId, ProgramRepository $programRepository, SeasonRepository $seasonRepository, EpisodeRepository $episodeRepository)
  {
    $program = $programRepository->findOneById($programId);
    $season = $seasonRepository->findById($seasonId);
    $episodes = $episodeRepository->findBySeason($seasonId);
    return $this->render('program/season_show.html.twig', [
      'program' => $program,
      'season' => $season,
      'episodes' => $episodes,
    ]);
  }

  #[Route('/{programId<\d+>}/season/{seasonId<\d+>}/episode/{episodeId<\d+>}', methods: ['GET'], name: 'episode_show')]
  public function showEpisode(int $programId, int $seasonId, int $episodeId, ProgramRepository $programRepository, SeasonRepository $seasonRepository, EpisodeRepository $episodeRepository)
  {
    $program = $programRepository->findOneById($programId);
    $season = $seasonRepository->findById($seasonId);
    $episode = $episodeRepository->findById($episodeId);
    return $this->render('program/episode_show.html.twig', [
      'program' => $program,
      'season' => $season,
      'episode' => $episode,
    ]);
  }

  #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, ProgramRepository $programRepository): Response
    {
        $program = new Program();
        $form = $this->createForm(ProgramType::class, $program);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $programRepository->add($program, true);

            return $this->redirectToRoute('program_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('program/new.html.twig', [
            'program' => $program,
            'form' => $form,
        ]);
    }
}
