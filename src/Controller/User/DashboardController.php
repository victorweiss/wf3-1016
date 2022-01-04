<?php

namespace App\Controller\User;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    #[Route('/', name: 'user_dashboard')]
    public function index(): Response
    {

        return $this->render('user/dashboard/index.html.twig', [

        ]);
    }
}
