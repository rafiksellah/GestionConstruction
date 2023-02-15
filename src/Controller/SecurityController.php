<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            if ($this->getUser()->getRoles()=="ROLE_USER")
            return $this->redirectToRoute('app_client');
            elseif($this->getUser()->getRoles()[0]=="ROLE_DECORTIQUEUR") {
                return $this->redirectToRoute('app_decortiqueur_plan_deco_index');
            }
            elseif($this->getUser()->getRoles()[0]=="ROLE_ADMIN") {
                return $this->redirectToRoute('app_admin');

            }
        }
        else
        {

            // get the login error if there is one
            $error = $authenticationUtils->getLastAuthenticationError();
            // last username entered by the user
            $lastUsername = $authenticationUtils->getLastUsername();
    
            return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
        }

    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
