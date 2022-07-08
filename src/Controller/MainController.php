<?php

namespace App\Controller;

use App\Repository\ShootaroundRepository;
use App\Repository\TeamRepository;
use App\Repository\UsersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class MainController extends AbstractController
{

    #[Route('/main', name: 'app_main')]
    public function index(): Response
    {
        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }

    /**
     * Nécessite le ROLE_COACH ou ADMIN pour accéder à cette route.
     *
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_COACH')", message="Vous n'avez pas accès à cette page")
     */
    #[Route('/compare', name: 'app_compare')]
    public function compare(ChartBuilderInterface $chartBuilder, ShootaroundRepository $shootaroundRepository, TeamRepository $teamRepository, UsersRepository $usersRepository): Response
    {

        $chart = $chartBuilder->createChart(Chart::TYPE_LINE);
        $chart->setData([
            'labels' => ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
            'datasets' => [
                [
                    'label' => 'Julien',
                    'backgroundColor' => 'rgb(255, 99, 132)',
                    'borderColor' => 'rgb(255, 99, 132)',
                    'data' => [50, 80, 45, 62, 60, 50, 60, 45],
                ],
                [
                    'label' => 'Laurence',
                    'backgroundColor' => 'rgb(66, 135, 245)',
                    'borderColor' => 'rgb(66, 135, 245)',
                    'data' => [60, 72, 55, 42, 86, 70, 80, 95],
                ],
                [
                    'label' => 'Stéphane',
                    'backgroundColor' => 'rgb(175, 225, 175)',
                    'borderColor' => 'rgb(175, 225, 175)',
                    'data' => [35, 62, 57, 75, 68, 62, 75, 72],
                ],
            ],
        ]);

        $chart->setOptions([
            'responsive' => true,
            'scales' => [
                'y' => [
                    'suggestedMin' => 0,
                    'suggestedMax' => 100,
                ],
            ],
        ]);
        return $this->render('compare/index.html.twig', [
            'controller_name' => 'MainController',
            'chart' => $chart,
            'shootaround' => $shootaroundRepository->findAll(),
            'teams' => $teamRepository->findAll(),
            'users' => $usersRepository->findAll()
        ]);
    }

    /**
     * Nécessite le ROLE_PLAYER ou ADMIN pour accéder à cette route.
     *
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_PLAYER')", message="Vous n'avez pas accès à cette page")
     */
    #[Route('/stat', name: 'app_stat')]
    public function stat(ChartBuilderInterface $chartBuilder, ShootaroundRepository $shootaroundRepository, TeamRepository $teamRepository, UsersRepository $usersRepository): Response
    {

        $stat = $chartBuilder->createChart(Chart::TYPE_LINE);
        $stat->setData([
            'labels' => ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
            'datasets' => [
                [
                    'label' => 'Julien',
                    'backgroundColor' => 'rgb(255, 99, 132)',
                    'borderColor' => 'rgb(255, 99, 132)',
                    'data' => [50, 80, 45, 62, 60, 50, 60, 45],
                ]
            ],
        ]);

        $stat->setOptions([
            'responsive' => true,
            'scales' => [
                'y' => [
                    'suggestedMin' => 0,
                    'suggestedMax' => 100,
                ],
            ],
        ]);
        return $this->render('compare/index.html.twig', [
            'controller_name' => 'MainController',
            'chart' => $stat,
            'shootaround' => $shootaroundRepository->findAll(),
            'teams' => $teamRepository->findAll(),
            'users' => $usersRepository->findAll()
        ]);
    }
}
