<?php

namespace App\Controller\Admin;

use App\Entity\Decortiqueur;
use App\Form\DecortiqueurType;
use App\Repository\DecortiqueurRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('/admin/decortiqueur')]
class DecortiqueurController extends AbstractController
{
    #[Route('/', name: 'app_admin_decortiqueur_index', methods: ['GET'])]
    public function index(DecortiqueurRepository $decortiqueurRepository): Response
    {
        return $this->render('admin/decortiqueur/index.html.twig', [
            'decortiqueurs' => $decortiqueurRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_admin_decortiqueur_new', methods: ['GET', 'POST'])]
    public function new(Request $request,UserPasswordHasherInterface $passwordHasher, DecortiqueurRepository $decortiqueurRepository): Response
    {
        $decortiqueur = new Decortiqueur();
        $form = $this->createForm(DecortiqueurType::class, $decortiqueur);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // dd($decortiqueur->getUser());
            $hashedPassword = $passwordHasher->hashPassword(
                $decortiqueur->getUser(),
                $form->get('user')->get('plainPassword')->getData()
            );
            $decortiqueur->getUser()->setPassword($hashedPassword);
            $decortiqueur->getUser()->setRoles(['ROLE_DECORTIQUEUR']);
            $decortiqueur->getUser()->setIsClient(false);
            $decortiqueurRepository->save($decortiqueur, true);

            return $this->redirectToRoute('app_admin_decortiqueur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/decortiqueur/new.html.twig', [
            'decortiqueur' => $decortiqueur,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_decortiqueur_show', methods: ['GET'])]
    public function show(Decortiqueur $decortiqueur): Response
    {
        return $this->render('admin/decortiqueur/show.html.twig', [
            'decortiqueur' => $decortiqueur,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_decortiqueur_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Decortiqueur $decortiqueur, DecortiqueurRepository $decortiqueurRepository): Response
    {
        $form = $this->createForm(DecortiqueurType::class, $decortiqueur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $decortiqueurRepository->save($decortiqueur, true);

            return $this->redirectToRoute('app_admin_decortiqueur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/decortiqueur/edit.html.twig', [
            'decortiqueur' => $decortiqueur,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_decortiqueur_delete', methods: ['POST'])]
    public function delete(Request $request, Decortiqueur $decortiqueur, DecortiqueurRepository $decortiqueurRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$decortiqueur->getId(), $request->request->get('_token'))) {
            $decortiqueurRepository->remove($decortiqueur, true);
        }

        return $this->redirectToRoute('app_admin_decortiqueur_index', [], Response::HTTP_SEE_OTHER);
    }
}
