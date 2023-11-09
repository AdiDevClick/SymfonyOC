<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PwdResetController extends AbstractController
{
    #[Route('/pwdreset', name: 'app_pwd_reset')]
    public function index(): Response
    {
        return $this->render('pwd_reset/index.html.twig', [
            'controller_name' => 'PwdResetController',
        ]);
    }
}
