<?php

namespace App\Controller;

use App\Repository\ShootaroundRepository;
use App\Repository\TeamRepository;
use App\Repository\UsersRepository;
use DateInterval;
use DateTime;
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

        $month = 12;
        $arrayMonths = [];
        $arrayDatas = [];
        for ($i = $month; $i >= 0; $i--) {
            $dateFirstDay = new DateTime();
            $dateFirstDay->setDate($dateFirstDay->format("Y"), $dateFirstDay->format("m"), 1);
            $arrayMonths[$dateFirstDay->sub((new DateInterval("P{$i}M")))->format("m/Y")] = 0;
        }

        foreach ($arrayMonths as $key => $arrayMonth) {
            $users = $usersRepository->findAll();
            foreach ($users as $user) {

                for ($i = $month; $i >= 0; $i--) {
                    $dateFirstDay = new DateTime();
                    $dateFirstDay->setDate($dateFirstDay->format("Y"), $dateFirstDay->format("m"), 1);
                    $arrayMonths[$dateFirstDay->sub((new DateInterval("P{$i}M")))->format("m/Y")] = 0;
                }
                $dateOk = substr($key, -4) . "-" . substr($key, 0, 2);
                $countRequest = $shootaroundRepository->createQueryBuilder('shoot')
                    ->select('AVG(shoot.percentage)')
                    ->andWhere('shoot.date BETWEEN :start AND :end AND shoot.user = :user')
                    ->setParameter('start', $dateOk . "-01 00:00:00")
                    ->setParameter('end', $dateOk . "-31 23:59:59")
                    ->setParameter('user', $user)
                    ->groupBy('shoot.user')
                    ->orderBy('shoot.date', 'ASC')
                    ->getQuery()
                    ->getResult();
                if (array_search("ROLE_PLAYER", $user->getRoles()) > -1) $arrayDatas[$user->getUsername()][$key] = $countRequest ? $countRequest[0][1] : 0;
            }
        }

        $chart = $chartBuilder->createChart(Chart::TYPE_LINE);
        $datasets = [];
        $colors = 0;
        foreach ($arrayDatas as $key => $data) {
            $color = $getColors->color[$colors] ?? "rgb(" . rand(0, 255) . ", " . rand(0, 255) . ", " . rand(0, 255) . ")";
            $datasets[] = [
                'label' => $key,
                'backgroundColor' => $color,
                'borderColor' => $color,
                'data' => $data
            ];
            $colors++;

        }
        $chart->setData([
            'labels' => array_keys($arrayMonths),
            'datasets' => $datasets
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

        $month = 12;
        $arrayMonths = [];
        $arrayDatas = [];
        for ($i = $month; $i >= 0; $i--) {
            $dateFirstDay = new DateTime();
            $dateFirstDay->setDate($dateFirstDay->format("Y"), $dateFirstDay->format("m"), 1);
            $arrayMonths[$dateFirstDay->sub((new DateInterval("P{$i}M")))->format("m/Y")] = 0;
        }

        foreach ($arrayMonths as $key => $arrayMonth) {
            $users = $usersRepository->findBy(['id' => $this->getUser()->getId()]);
            foreach ($users as $user) {

                for ($i = $month; $i >= 0; $i--) {
                    $dateFirstDay = new DateTime();
                    $dateFirstDay->setDate($dateFirstDay->format("Y"), $dateFirstDay->format("m"), 1);
                    $arrayMonths[$dateFirstDay->sub((new DateInterval("P{$i}M")))->format("m/Y")] = 0;
                }
                $dateOk = substr($key, -4) . "-" . substr($key, 0, 2);
                $countRequest = $shootaroundRepository->createQueryBuilder('shoot')
                    ->select('AVG(shoot.percentage)')
                    ->andWhere('shoot.date BETWEEN :start AND :end AND shoot.user = :user')
                    ->setParameter('start', $dateOk . "-01 00:00:00")
                    ->setParameter('end', $dateOk . "-31 23:59:59")
                    ->setParameter('user', $user)
                    ->groupBy('shoot.user')
                    ->orderBy('shoot.date', 'ASC')
                    ->getQuery()
                    ->getResult();
                if (array_search("ROLE_PLAYER", $user->getRoles()) > -1) $arrayDatas[$user->getUsername()][$key] = $countRequest ? $countRequest[0][1] : 0;
            }
        }

        $stat = $chartBuilder->createChart(Chart::TYPE_LINE);
        $datasets = [];
        $colors = 0;
        foreach ($arrayDatas as $key => $data) {
            $color = $getColors->color[$colors] ?? "rgb(" . rand(0, 255) . ", " . rand(0, 255) . ", " . rand(0, 255) . ")";
            $datasets[] = [
                'label' => $key,
                'backgroundColor' => $color,
                'borderColor' => $color,
                'data' => $data
            ];
            $colors++;

        }
        $stat->setData([
            'labels' => array_keys($arrayMonths),
            'datasets' => $datasets
        ]);

//        $stat = $chartBuilder->createChart(Chart::TYPE_LINE);
//        $stat->setData([
//            'labels' => ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
//            'datasets' => [
//                [
//                    'label' => 'Julien',
//                    'backgroundColor' => 'rgb(255, 99, 132)',
//                    'borderColor' => 'rgb(255, 99, 132)',
//                    'data' => [50, 80, 45, 62, 60, 50, 60, 45],
//                ],
//            ],
//        ]);

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
