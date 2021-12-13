<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BaseController extends AbstractController
{
    public function _header(): Response
    {
        // Requete BDD
        $articles = [
            ['title' => "Article 1"],
            ['title' => "Article 2"],
            ['title' => "Article 3"],
        ];

        return $this->render('partials/_header.html.twig', [
            'articles' => $articles
        ]);
    }
}
