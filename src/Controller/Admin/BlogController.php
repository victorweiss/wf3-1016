<?php

namespace App\Controller\Admin;

use App\Entity\BlogPost;
use App\Form\BlogPostType;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

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
    public function create(
        Request $request,
        SluggerInterface $slugger,
        EntityManagerInterface $entityManager
    ): Response
    {
        $post = new BlogPost();
        $form = $this->createForm(BlogPostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // TODO : slug unique !
            // 1. Remplir les propriétés manquantes
            $slug = $slugger->slug($post->getTitle())->lower();
            $post->setSlug($slug);

            // 2 Enregistrer le post en BDD
            $entityManager->persist($post);
            $entityManager->flush();

            $this->addFlash('success', "Les modifications ont été enregistrées");
            return $this->redirectToRoute('admin_blog_update', ['slug' => $post->getSlug()]);
        }

        return $this->render('admin/blog/create.html.twig', [
            'form' => $form->createView(),
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
