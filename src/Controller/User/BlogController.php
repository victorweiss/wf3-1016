<?php

namespace App\Controller\User;

use App\Entity\BlogPost;
use App\Form\BlogPostType;
use App\Repository\BlogPostRepository;
use App\Security\Voter\BlogPostVoter;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/a/espace-membre/blog')]
class BlogController extends AbstractController
{
    #[Route('', name: 'user_blog_index')]
    public function index(BlogPostRepository $blogPostRepository): Response
    {
        if ($this->isGranted('ROLE_ADMIN')) {
            $posts = $blogPostRepository->findAll();
        } else {
            $posts = $blogPostRepository->findBy(['user' => $this->getUser()]);
        }

        return $this->render('admin/blog/index.html.twig', [
            'posts' => $posts
        ]);
    }

    #[Route('/nouveau', name: 'user_blog_create')]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $post = new BlogPost();
        $post->setUser($this->getUser());
        return $this->_form('create', $post, $request, $entityManager);
    }

    #[Route('/{slug}', name: 'user_blog_update')]
    #[IsGranted(BlogPostVoter::EDIT, subject: 'post')]
    public function update(BlogPost $post, Request $request, EntityManagerInterface $entityManager): Response
    {
        return $this->_form('update', $post, $request, $entityManager);
    }

    private function _form(string $action, BlogPost $post, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(BlogPostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($post);
            $entityManager->flush();

            $this->addFlash('success', "Les modifications ont été enregistrées");
            return $this->redirectToRoute('user_blog_update', ['slug' => $post->getSlug()]);
        }

        return $this->render("admin/blog/$action.html.twig", [
            'form' => $form->createView(),
            'action' => $action
        ]);
    }

    #[Route('/{slug}/delete', name: 'user_blog_delete')]
    #[IsGranted(BlogPostVoter::DELETE, subject: 'post')]
    public function delete(BlogPost $post, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($post);
        $entityManager->flush();
        $this->addFlash('success', "L'article a bien été supprimé.");
        return $this->redirectToRoute('user_blog_index');
    }

    #[Route('/{slug}/status/{newStatus}', name: 'user_blog_status', requirements: ['newStatus' => 'active|draft'])]
    #[IsGranted(BlogPostVoter::EDIT, subject: 'post')]
    public function status(BlogPost $post, string $newStatus, EntityManagerInterface $entityManager): Response
    {
        $post->setStatus($newStatus);
        $entityManager->flush();
        return $this->redirectToRoute('user_blog_index');
    }
}
