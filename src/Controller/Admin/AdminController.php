<?php

namespace App\Controller\Admin;

use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[IsGranted('ROLE_ADMIN')]
#[Route('/admin')]
class AdminController extends AbstractController
{
    
    #[Route('/', name: 'app_admin')]
    public function index(): Response
    {
        
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

}
