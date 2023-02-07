<?php

namespace App\Controller;

use App\Entity\Plan;
use App\Form\PlanType;
use App\Entity\Fichiers;
use App\Repository\PlanRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/client/plan')]
class PlanController extends AbstractController
{
    #[Route('/', name: 'app_plan_index', methods: ['GET'])]
    public function index(PlanRepository $planRepository): Response
    {
        $user = $this->getUser();
        if ($user->getRoles()[0] == "ROLE_USER") {
            $plans = $planRepository->findBy(["user"=>$user->getId()]);
        }
        return $this->render('plan/index.html.twig', [
            'plans' => $plans,
        ]);
    }

    #[Route('/new', name: 'app_plan_new', methods: ['GET', 'POST'])]
    public function new(Request $request, PlanRepository $planRepository): Response
    {
        $plan = new Plan();
        $form = $this->createForm(PlanType::class, $plan);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $fichiers = $form->get('fichiers')->getData();
           
            // On boucle sur les images
            foreach($fichiers as $fich){
                // On génère un nouveau nom de fichier
                $fichier = md5(uniqid()).'.'.$fich->guessExtension();
                // On copie le fichier dans le dossier uploads
                $fich->move(
                    $this->getParameter('fichierdecor_directory'),
                    $fichier
                );
                
                // On crée l'image dans la base de données
                $file = new Fichiers();
                $file->setName($fichier);
                $plan->addFichier($file);
            }
            $plan->setUser($this->getUser());
            $plan->setCreatedAt(new \DateTime('now'));
            $plan->setEtat(Plan::PLAN_STATUS_ENATTENTE);
            $planRepository->save($plan, true);


            return $this->redirectToRoute('app_plan_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('plan/new.html.twig', [
            'plan' => $plan,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_plan_show', methods: ['GET'])]
    public function show(Plan $plan): Response
    {
        return $this->render('plan/show.html.twig', [
            'plan' => $plan,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_plan_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Plan $plan, PlanRepository $planRepository): Response
    {
        $form = $this->createForm(PlanType::class, $plan);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $planRepository->save($plan, true);

            return $this->redirectToRoute('app_plan_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('plan/edit.html.twig', [
            'plan' => $plan,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_plan_delete', methods: ['POST'])]
    public function delete(Request $request, Plan $plan, PlanRepository $planRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$plan->getId(), $request->request->get('_token'))) {
            $planRepository->remove($plan, true);
        }

        return $this->redirectToRoute('app_plan_index', [], Response::HTTP_SEE_OTHER);
    }
}
