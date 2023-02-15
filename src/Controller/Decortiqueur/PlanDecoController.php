<?php

namespace App\Controller\Decortiqueur;

use App\Entity\Plan;
use App\Form\Plan2Type;
use App\Form\PlanDecoType;
use App\Entity\FichierDecor;
use App\Repository\PlanRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[IsGranted('ROLE_DECORTIQUEUR')]
#[Route('/decortiqueur/plan')]
class PlanDecoController extends AbstractController
{
    #[Route('/', name: 'app_decortiqueur_plan_deco_index', methods: ['GET'])]
    public function index(PlanRepository $planRepository): Response
    {
        $plans = $planRepository->findAll();
        return $this->render('decortiqueur/plan_deco/index.html.twig', [
            'plans' => $plans
        ]);
    }

    #[Route('/new', name: 'app_decortiqueur_plan_deco_new', methods: ['GET', 'POST'])]
    public function new(Request $request, PlanRepository $planRepository): Response
    {
        $plan = new Plan();
        $form = $this->createForm(Plan2Type::class, $plan);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $planRepository->save($plan, true);

            return $this->redirectToRoute('app_decortiqueur_plan_deco_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('decortiqueur/plan_deco/new.html.twig', [
            'plan' => $plan,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_decortiqueur_plan_deco_show', methods: ['GET'])]
    public function show(Plan $plan): Response
    {
        return $this->render('decortiqueur/plan_deco/show.html.twig', [
            'plan' => $plan,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_decortiqueur_plan_deco_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Plan $plan, PlanRepository $planRepository): Response
    {
        $form = $this->createForm(Plan2Type::class, $plan);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $planRepository->save($plan, true);

            return $this->redirectToRoute('app_decortiqueur_plan_deco_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('decortiqueur/plan_deco/edit.html.twig', [
            'plan' => $plan,
            'form' => $form,
        ]);
    }

    #[Route('/affichage/decortiquer/{id}', name: 'app_decortiqueur_plan_deco_afficher', methods: ['GET'])]
    public function  affichDecortiquer(Request $request, Plan $plan, PlanRepository $planRepository): Response
    {
        $deco = $this->getUser();
        return $this->renderForm('decortiqueur/plan_deco/affiche.html.twig', [
            'plan' => $plan,
        ]);
    }
    
    #[Route('/decortiquer/{id}', name: 'app_decortiqueur_plan_deco_decortiquer', methods: ['POST'])]
    public function decortiquer(Request $request, Plan $plan, PlanRepository $planRepository): Response
    {
        $deco = $this->getUser();
        if ($this->isCsrfTokenValid('decortique'.$plan->getId(), $request->request->get('_token'))) {
            $plan->setEtat(PLAN::PLAN_STATUS_ENCOURS);
            $plan->setDecortiqueurs($deco);
            $planRepository->save($plan, true);
        }
        return $this->redirectToRoute('app_decortiqueur_plan_deco_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/verification/{id}', name: 'app_decortiqueur_plan_verification_decortiquer')]
    public function verfication(Request $request, Plan $plan, PlanRepository $planRepository): Response
    {
        $form = $this->createForm(PlanDecoType::class, $plan, [
            'action' => $request->getRequestUri()
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() ) { 
            $fichiers = $form->get('fichierDecor')->getData();
           
            // On boucle sur les images
            foreach($fichiers as $fich){
                // On génère un nouveau nom de fichier
                $fichier = md5(uniqid()).'.'.$fich->guessExtension();
                // On copie le fichier dans le dossier uploads
                $fich->move(
                    $this->getParameter('fichier_directory'),
                    $fichier
                );
                
                // On crée l'image dans la base de données
                $file = new FichierDecor();
                $file->setName($fichier);
                $plan->addFiechierDeco($file);
            }
            $deco = $this->getUser();
            $plan->setDecortiqueurs($deco);
            $planRepository->save($plan, true);
            $this->addFlash('success', 'Votre demande a été envoyé ');


            // return $this->redirectToRoute('app_decortiqueur_plan_deco_index', [], Response::HTTP_SEE_OTHER);
        }


        return $this->renderForm('decortiqueur/plan_deco/plan_deco.html.twig', [
            'form' => $form,
            'plan' => $plan,
        ]);
    }


    #[Route('/terminer/{id}', name: 'app_decortiqueur_plan_deco_terminer', methods: ['POST'])]
    public function terminer(Request $request, Plan $plan, PlanRepository $planRepository): Response
    {

        $deco = $this->getUser();
        if ($this->isCsrfTokenValid('terminer'.$plan->getId(), $request->request->get('_token'))) {
            $plan->setEtat(PLAN::PLAN_STATUS_ACTIVE);
            $plan->setDecortiqueurs($deco);
            $planRepository->save($plan, true);
        }

        return $this->redirectToRoute('app_decortiqueur_plan_deco_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/{id}', name: 'app_decortiqueur_plan_deco_delete', methods: ['POST'])]
    public function delete(Request $request, Plan $plan, PlanRepository $planRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$plan->getId(), $request->request->get('_token'))) {
            $planRepository->remove($plan, true);
        }

        return $this->redirectToRoute('app_decortiqueur_plan_deco_index', [], Response::HTTP_SEE_OTHER);
    }
}
