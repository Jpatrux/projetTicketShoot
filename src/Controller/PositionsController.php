<?php

namespace App\Controller;

use App\Entity\Positions;
use App\Form\PositionsType;
use App\Repository\PositionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * Nécessite le ROLE_ADMIN pour accéder à cette route.
 *
 * @IsGranted("ROLE_ADMIN", message="Vous n'avez pas accès à cette page")
 */
#[Route('/positions')]
class PositionsController extends AbstractController
{
    #[Route('/', name: 'app_positions_index', methods: ['GET'])]
    public function index(PositionRepository $positionRepository): Response
    {
        return $this->render('positions/index.html.twig', [
            'positions' => $positionRepository->findAll(),
        ]);
    }

    /**
     * Nécessite le ROLE_ADMIN pour accéder à cette route.
     *
     * @IsGranted("ROLE_ADMIN", message="Vous n'avez pas accès à cette page")
     */
    #[Route('/new', name: 'app_positions_new', methods: ['GET', 'POST'])]
    public function new(Request $request, PositionRepository $positionRepository): Response
    {
        $position = new Positions();
        $form = $this->createForm(PositionsType::class, $position);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $positionRepository->add($position, true);

            return $this->redirectToRoute('app_positions_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('positions/new.html.twig', [
            'position' => $position,
            'form' => $form,
        ]);
    }

    /**
     * Nécessite le ROLE_ADMIN pour accéder à cette route.
     *
     * @IsGranted("ROLE_ADMIN", message="Vous n'avez pas accès à cette page")
     */
    #[Route('/{id}', name: 'app_positions_show', methods: ['GET'])]
    public function show(Positions $position): Response
    {
        return $this->render('positions/show.html.twig', [
            'position' => $position,
        ]);
    }

    /**
     * Nécessite le ROLE_ADMIN pour accéder à cette route.
     *
     * @IsGranted("ROLE_ADMIN", message="Vous n'avez pas accès à cette page")
     */
    #[Route('/{id}/edit', name: 'app_positions_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Positions $position, PositionRepository $positionRepository): Response
    {
        $form = $this->createForm(PositionsType::class, $position);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $positionRepository->add($position, true);

            return $this->redirectToRoute('app_positions_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('positions/edit.html.twig', [
            'position' => $position,
            'form' => $form,
        ]);
    }

    /**
     * Nécessite le ROLE_ADMIN pour accéder à cette route.
     *
     * @IsGranted("ROLE_ADMIN", message="Vous n'avez pas accès à cette page")
     */
    #[Route('/{id}', name: 'app_positions_delete', methods: ['POST'])]
    public function delete(Request $request, Positions $position, PositionRepository $positionRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$position->getId(), $request->request->get('_token'))) {
            $positionRepository->remove($position, true);
        }

        return $this->redirectToRoute('app_positions_index', [], Response::HTTP_SEE_OTHER);
    }
}
