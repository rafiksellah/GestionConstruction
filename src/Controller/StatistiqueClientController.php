<?php

namespace App\Controller;

use App\Entity\Plan;
use App\Form\DateRange;
use App\Form\DateRangeType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StatistiqueClientController extends AbstractController
{
    #[Route('client/statistique', name: 'client_statistique')]
    public function displayStatisticsUser(Request $request, ManagerRegistry $mr)
    {
        $dateRange = new DateRange();
        $user = $this->getUser();
        $form = $this->createForm(DateRangeType::class, $dateRange);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $mr->getManager();
            $query = $entityManager->createQueryBuilder();
            $query->select('u.entreprise,u.id, SUM(p.tonnageTS) as sum2,SUM(p.tonnageCA) as sum3, SUM(p.tonnageCF) as sum4')
                  ->from(Plan::class, 'p')
                  ->join('p.user', 'u')
                  ->where('p.date BETWEEN :startDate AND :endDate')
                  ->andWhere('p.user = :user')
                  ->groupBy('p.user')
                  ->setParameter('startDate', $dateRange->getStartDate())
                  ->setParameter('endDate', $dateRange->getEndDate())
                  ->setParameter('user', $user);

            $totals = $query->getQuery()->getResult();
            
            
        }

        return $this->render('client/statistique.html.twig', [
            'form' => $form->createView(),
            'totals' => $totals ?? 0,
        ]);
    }

   
}
