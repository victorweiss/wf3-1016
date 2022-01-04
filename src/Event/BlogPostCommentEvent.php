<?php

namespace App\Event;

use App\Entity\BlogPostComment;

class BlogPostCommentEvent
{
    const PUBLISHED = 'comment.published';

    public function __construct(
        private BlogPostComment $comment
    ) {}

    public function getComment(): BlogPostComment
    {
        return $this->comment;
    }
}
