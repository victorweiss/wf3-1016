<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/blog')]
class BlogController extends AbstractController
{
    #[Route('', name: 'admin_blog_index')]
    public function index(): Response
    {
        return $this->render('admin/blog/index.html.twig', [

        ]);
    }

    #[Route('/nouveau', name: 'admin_blog_create')]
    public function create(): Response
    {
        return $this->render('admin/blog/create.html.twig', [

        ]);
    }

    #[Route('/{slug}', name: 'admin_blog_update')]
    public function update(): Response
    {
        return $this->render('admin/blog/update.html.twig', [

        ]);
    }

    #[Route('/{slug}/delete', name: 'admin_blog_delete')]
    public function delete(): Response
    {
        return $this->redirectToRoute('admin_blog_index');
    }
}
