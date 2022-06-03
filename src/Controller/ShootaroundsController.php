<?php

namespace App\Controller;

use App\Entity\Shootarounds;
use App\Form\ShootaroundsType;
use App\Repository\ShootaroundRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;



#[Route('/shootarounds')]
class ShootaroundsController extends AbstractController
{
    /**
     * Nécessite le ROLE_PLAYER ou ADMIN pour accéder à cette route.
     *
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_PLAYER')", message="Vous n'avez pas accès à cette page")
     */
    #[Route('/', name: 'app_shootarounds_index', methods: ['GET'])]
    public function index(FormBuilderInterface $builder, ShootaroundRepository $shootaroundRepository): Response
    {
        //$dateRequest = $shootaroundRepository->createQueryBuilder('shoot')
        //    ->andWhere('shoot.date BETWEEN :start AND :end ')
        //    ->setParameter('start', $dateSearch . "input1")
        //    ->setParameter('end', $dateSearch . "input2")
        //    ->orderBy("shoot.id", "ASC")
        //    ->getQuery()
        //    ->getResult();

        //$builder->add('start', DateType::class, [
            // renders it as a single text box
        //    'widget' => 'single_text',
        //]);
        //$builder->add('end', DateType::class, [
            // renders it as a single text box
        //    'widget' => 'single_text',
        //]);

        return $this->render('shootarounds/index.html.twig', [
            'shootarounds' => $shootaroundRepository->findAll(),
        ]);
    }

    #[Route('/date/{dateStart}/{dateEnd}', name: 'app_shootaroundsDate_index', methods: ['GET'])]
    public function indexByDate(FormBuilderInterface $builder, ShootaroundRepository $shootaroundRepository, $dateStart, $dateEnd): Response
    {
        return $this->render('shootarounds/date.html.twig', [
            'shootarounds' => $shootaroundRepository->findByDate($dateStart, $dateEnd),
        ]);
    }



    /**
     * Nécessite le ROLE_PLAYER ou ADMIN pour accéder à cette route.
     *
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_PLAYER')", message="Vous n'avez pas accès à cette page")
     */
    #[Route('/new', name: 'app_shootarounds_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ShootaroundRepository $shootaroundRepository): Response
    {
        $request->getMethod();
        $shootaround = new Shootarounds();
        $form = $this->createForm(ShootaroundsType::class, $shootaround);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $shootaround->setPercentage();
            $shootaroundRepository->add($shootaround, true);

            return $this->redirectToRoute('app_shootarounds_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('shootarounds/new.html.twig', [
            'shootaround' => $shootaround,
            'form' => $form,
        ]);
    }

    /**
     * Nécessite le ROLE_PLAYER ou ADMIN pour accéder à cette route.
     *
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_PLAYER')", message="Vous n'avez pas accès à cette page")
     */
    #[Route('/{id}', name: 'app_shootarounds_show', methods: ['GET'])]
    public function show(Shootarounds $shootaround): Response
    {
        return $this->render('shootarounds/show.html.twig', [
            'shootaround' => $shootaround,
        ]);
    }

    /**
     * Nécessite le ROLE_PLAYER ou ADMIN pour accéder à cette route.
     *
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_PLAYER')", message="Vous n'avez pas accès à cette page")
     */
    #[Route('/{id}/edit', name: 'app_shootarounds_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Shootarounds $shootaround, ShootaroundRepository $shootaroundRepository): Response
    {
        $form = $this->createForm(ShootaroundsType::class, $shootaround);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $shootaroundRepository->add($shootaround, true);

            return $this->redirectToRoute('app_shootarounds_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('shootarounds/edit.html.twig', [
            'shootaround' => $shootaround,
            'form' => $form,
        ]);
    }

    /**
     * Nécessite le ROLE_PLAYER ou ADMIN pour accéder à cette route.
     *
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_PLAYER')", message="Vous n'avez pas accès à cette page")
     */
    #[Route('/{id}', name: 'app_shootarounds_delete', methods: ['POST'])]
    public function delete(Request $request, Shootarounds $shootaround, ShootaroundRepository $shootaroundRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$shootaround->getId(), $request->request->get('_token'))) {
            $shootaroundRepository->remove($shootaround, true);
        }

        return $this->redirectToRoute('app_shootarounds_index', [], Response::HTTP_SEE_OTHER);
    }
}
