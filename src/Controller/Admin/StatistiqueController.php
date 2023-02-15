<?php

namespace App\Controller\Admin;

use App\Entity\Plan;
use App\Form\DateRange;
use App\Form\DateRangeType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StatistiqueController extends AbstractController
{
    #[Route('admin/statistique', name: 'app_statistique')]
    public function displayStatistics(Request $request, ManagerRegistry $mr)
    {
        $dateRange = new DateRange();
        $form = $this->createForm(DateRangeType::class, $dateRange);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $mr->getManager();
            $query = $entityManager->createQueryBuilder();
            $query->select('u.entreprise,u.id, SUM(p.tonnageTS) as sum2,SUM(p.tonnageCA) as sum3, SUM(p.tonnageCF) as sum4')
                  ->from(Plan::class, 'p')
                  ->join('p.user', 'u')
                  ->where('p.date BETWEEN :startDate AND :endDate')
                  ->groupBy('p.user')
                  ->setParameter('startDate', $dateRange->getStartDate())
                  ->setParameter('endDate', $dateRange->getEndDate());

            $totals = $query->getQuery()->getResult();
            
        }

        return $this->render('admin/statistique/index.html.twig', [
            'form' => $form->createView(),
            'totals' => $totals ?? 0,
        ]);
    }

    #[Route('admin/statistique/decortiqueur', name: 'app_statistique_deco')]
    public function displayStatisticsDeco(Request $request, ManagerRegistry $mr)
    {
        $dateRange = new DateRange();
        $form = $this->createForm(DateRangeType::class, $dateRange);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $mr->getManager();
            $query = $entityManager->createQueryBuilder();
            $query->select('d.nom,d.id, SUM(p.tonnageTS) as sum2,SUM(p.tonnageCA) as sum3, SUM(p.tonnageCF) as sum4')
                  ->from(Plan::class, 'p')
                  ->join('p.decortiqueurs', 'd')
                  ->where('p.date BETWEEN :startDate AND :endDate')
                  ->groupBy('p.decortiqueurs')
                  ->setParameter('startDate', $dateRange->getStartDate())
                  ->setParameter('endDate', $dateRange->getEndDate());

            $totals = $query->getQuery()->getResult();
            
        }

        return $this->render('admin/statistique/statistiqueDeco.html.twig', [
            'form' => $form->createView(),
            'totals' => $totals ?? 0,
        ]);
    }
}
