<?php

namespace App\Controller\Admin;

use DateTime;
use App\Entity\Plan;
use App\Form\Plan1Type;
use App\Entity\Fichiers;
use App\Repository\PlanRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[IsGranted('ROLE_ADMIN')]
#[Route('/admin/plan_admin')]
class PlanAdminController extends AbstractController
{
    #[Route('/', name: 'app_admin_plan_admin_index', methods: ['GET'])]
    public function index(PlanRepository $planRepository): Response
    {
        $etat=Plan::PLAN_STATUS_ACTIVE;
        $plans = $planRepository->findALLPlansNonTerminer($etat);
        return $this->render('admin/plan_admin/index.html.twig', [
            'plans' => $plans,
        ]);
    }
    
    #[Route('/plans_termines', name: 'app_admin_plan_plans_termines', methods: ['GET'])]
    public function plans_termines(PlanRepository $planRepository): Response
    {
        $etat=Plan::PLAN_STATUS_ACTIVE;
        $plans_termines = $planRepository->findBy(["etat"=>$etat]);
        return $this->render('admin/plan_admin/plan_termine.html.twig', [
            'plans_termines' => $plans_termines,
        ]);
    }
    
    #[Route('/new', name: 'app_admin_plan_admin_new', methods: ['GET', 'POST'])]
    public function new(Request $request, PlanRepository $planRepository): Response
    {
        $plan = new Plan();
        $form = $this->createForm(Plan1Type::class, $plan);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $fichiers = $form->get('fichiers')->getData();
           
            // On boucle sur les images
            foreach($fichiers as $fich){
                // On génère un nouveau nom de fichier
                $extension = $fich->guessExtension();
                $name = $fich->getClientOriginalName();
                // Créez un nom de fichier unique en utilisant la date et l'heure actuelles
                $fichier = 'file_' . $name. '.' . $extension;
                $fich->move(
                    $this->getParameter('fichier_directory'),
                    $fichier
                );
                
                // On crée l'image dans la base de données
                $file = new Fichiers();
                $file->setName($fichier);
                $plan->addFichier($file);
            }
            $plan->setCreatedAt(new DateTime('now'));
            $plan->setEtat(Plan::PLAN_STATUS_ENATTENTE);
            $planRepository->save($plan, true);

            return $this->redirectToRoute('app_admin_plan_admin_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/plan_admin/new.html.twig', [
            'plan' => $plan,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_plan_admin_show', methods: ['GET'])]
    public function show(Plan $plan): Response
    {
        return $this->render('admin/plan_admin/show.html.twig', [
            'plan' => $plan,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_plan_admin_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Plan $plan, PlanRepository $planRepository): Response
    {
        $form = $this->createForm(Plan1Type::class, $plan);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $planRepository->save($plan, true);

            return $this->redirectToRoute('app_admin_plan_admin_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/plan_admin/edit.html.twig', [
            'plan' => $plan,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_plan_admin_delete', methods: ['POST'])]
    public function delete(Request $request, Plan $plan, PlanRepository $planRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$plan->getId(), $request->request->get('_token'))) {
            $planRepository->remove($plan, true);
        }

        return $this->redirectToRoute('app_admin_plan_admin_index', [], Response::HTTP_SEE_OTHER);
    }
}
