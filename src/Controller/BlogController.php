<?php

namespace App\Controller;

use App\Entity\BlogPost;
use App\Entity\BlogPostComment;
use App\Event\BlogPostCommentEvent;
use App\Form\BlogPostCommentType;
use App\Repository\BlogPostRepository;
use App\Security\Voter\BlogPostVoter;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
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
    #[IsGranted(BlogPostVoter::VIEW, subject: 'post')]
    public function view(
        BlogPost $post,
        Request $request,
        EntityManagerInterface $entityManager,
        EventDispatcherInterface $dispatcher
    ): Response
    {
        // Retourne une 404 si l'article est en Draft
        // if ($post->getStatus() !== BlogPost::STATUS_ACTIVE) {
        //     throw $this->createNotFoundException();
        // }

        $comment = new BlogPostComment();
        $form = $this->createForm(BlogPostCommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment
                ->setUser($this->getUser())
                ->setBlogPost($post)
            ;

            $entityManager->persist($comment);
            $entityManager->flush();

            $dispatcher->dispatch(new BlogPostCommentEvent($comment), BlogPostCommentEvent::PUBLISHED);

            return $this->redirectToRoute('blog_view', [
                'slug' => $post->getSlug(),
                '_fragment' => 'comment-' . $comment->getId(),
            ]);
        }

        return $this->render('blog/view.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
        ]);
    }
}
