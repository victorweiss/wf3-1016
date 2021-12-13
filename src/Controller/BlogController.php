<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{
    #[Route('/blog', name: 'blog_listing')]
    public function blogListing(): Response
    {
        return $this->render('blog/listing.html.twig', [

        ]);
    }

    #[Route('/blog/slug', name: 'blog_article')]
    public function blogArticle(): Response
    {
        return $this->render('blog/article.html.twig', [

        ]);
    }
}
