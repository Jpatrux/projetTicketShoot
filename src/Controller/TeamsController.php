<?php

namespace App\Controller;

use App\Entity\Teams;
use App\Form\TeamsType;
use App\Repository\TeamRepository;
use App\Repository\UsersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

#[Route('/teams')]
class TeamsController extends AbstractController
{
    /**
     * Nécessite le ROLE_COACH ou ADMIN pour accéder à cette route.
     *
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_COACH')", message="Vous n'avez pas accès à cette page")
     */
    #[Route('/', name: 'app_teams_index', methods: ['GET'])]
    public function index(TeamRepository $teamRepository): Response
    {
        return $this->render('teams/index.html.twig', [
            'teams' => $teamRepository->findAll(),
        ]);
    }

    /**
     * Nécessite le ROLE_COACH ou ADMIN pour accéder à cette route.
     *
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_COACH')", message="Vous n'avez pas accès à cette page")
     */
    #[Route('/new', name: 'app_teams_new', methods: ['GET', 'POST'])]
    public function new(Request $request, TeamRepository $teamRepository): Response
    {
        $team = new Teams();
        $form = $this->createForm(TeamsType::class, $team);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $teamRepository->add($team, true);

            return $this->redirectToRoute('app_teams_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('teams/new.html.twig', [
            'team' => $team,
            'form' => $form,
        ]);
    }

    /**
     * Nécessite le ROLE_COACH ou ADMIN pour accéder à cette route.
     *
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_COACH')", message="Vous n'avez pas accès à cette page")
     */
    #[Route('/{id}', name: 'app_teams_show', methods: ['GET'])]
    public function show(Teams $team, UsersRepository $usersRepository): Response
    {
        return $this->render('teams/show.html.twig', [
            'team' => $team,
            'users' => $usersRepository->findAll()
        ]);
    }

    /**
     * Nécessite le ROLE_COACH ou ADMIN pour accéder à cette route.
     *
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_COACH')", message="Vous n'avez pas accès à cette page")
     */
    #[Route('/{id}/edit', name: 'app_teams_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Teams $team, TeamRepository $teamRepository): Response
    {
        $form = $this->createForm(TeamsType::class, $team);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $teamRepository->add($team, true);

            return $this->redirectToRoute('app_teams_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('teams/edit.html.twig', [
            'team' => $team,
            'form' => $form,
        ]);
    }

    /**
     * Nécessite le ROLE_COACH ou ADMIN pour accéder à cette route.
     *
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_COACH')", message="Vous n'avez pas accès à cette page")
     */
    #[Route('/{id}', name: 'app_teams_delete', methods: ['POST'])]
    public function delete(Request $request, Teams $team, TeamRepository $teamRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$team->getId(), $request->request->get('_token'))) {
            $teamRepository->remove($team, true);
        }

        return $this->redirectToRoute('app_teams_index', [], Response::HTTP_SEE_OTHER);
    }
}
