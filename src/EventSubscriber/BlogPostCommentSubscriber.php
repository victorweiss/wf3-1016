<?php

namespace App\EventSubscriber;

use App\Event\BlogPostCommentEvent;
use App\Service\NotifyService;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class BlogPostCommentSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private NotifyService $notifyService
    ) {}

    public static function getSubscribedEvents()
    {
        return [
            BlogPostCommentEvent::PUBLISHED => 'onCommentPublished',
        ];
    }

    public function onCommentPublished(BlogPostCommentEvent $event): void
    {
        $comment = $event->getComment();
        $post = $comment->getBlogPost();

        $emails = [];
        $emails[] = $post->getUser()->getEmail();

        foreach ($post->getComments() as $c) {
            $emails[] = $c->getUser()->getEmail();
        }

        $emails = array_unique($emails);

        foreach ($emails as $email) {
            $templatedEmail = (new TemplatedEmail())
                ->to($email)
                ->subject("Nouveau commentaire sur l'article : " . $post->getTitle())
                ->htmlTemplate('blog/email/comment_published.email.twig')
                ->context([
                    'post' => $post
                ]);
            $this->notifyService->sendEmail($templatedEmail);
        }
    }
}
