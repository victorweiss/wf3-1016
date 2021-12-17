<?php

namespace App\Controller;

use App\Entity\BlogPost;
use App\Repository\BlogPostRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/blog')]
class BlogController extends AbstractController
{
    #[Route('', name: 'blog_index')]
    public function index(
        BlogPostRepository $blogPostRepository,
        PaginatorInterface $paginator,
        Request $request
    ): Response
    {
        $pagination = $paginator->paginate(
            $blogPostRepository->findPublicPosts(),
            $request->query->getInt('page', 1),
            4
        );

        return $this->render('blog/index.html.twig', [
            'pagination' => $pagination
        ]);
    }

    #[Route('/{slug}', name: 'blog_view')]
    public function view(BlogPost $post): Response
    {
        return $this->render('blog/view.html.twig', [
            'post' => $post
        ]);
    }
}
