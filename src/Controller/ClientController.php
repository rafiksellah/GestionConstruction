<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserPasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ClientController extends AbstractController
{
    #[Route('/client', name: 'app_client')]
    public function index(): Response
    {
        $user =  $this->getUser();
        return $this->render('client/index.html.twig', [
            'user' => $user,
        ]);
    }
     /**
     * This controller allow us to edit user's password
     *
     * @param User $choosenUser
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param UserPasswordHasherInterface $hasher
     * @return Response
     */
    #[Security("is_granted('ROLE_USER')")]
    #[Route('/client/edition-mot-de-passe/{id}', 'user.edit.password', methods: ['GET', 'POST'])]
    public function editPassword(
        User $choosenUser,
        Request $request,
        EntityManagerInterface $manager,
        UserPasswordHasherInterface $hasher
    ): Response {
        $choosenUser = $this->getUser();
        $form = $this->createForm(UserPasswordType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $choosenUser->setPassword(
                $hasher->hashPassword(
                    $choosenUser,
                    $form->get('newPassword')->getData()
                )
                );

                $this->addFlash(
                    'success',
                    'Le mot de passe a été modifié.'
                );

                $manager->persist($choosenUser);
                $manager->flush();

                return $this->redirectToRoute('app_client');
        }

        return $this->render('client/edit_password.html.twig', [
            'form' => $form->createView()
        ]);
    }

}
