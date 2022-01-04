<?php

namespace App\Security\Voter;

use App\Entity\BlogPost;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;

class BlogPostVoter extends Voter
{
    const VIEW = 'view';
    const EDIT = 'edit';

    public function __construct(
        private Security $security
    ) {}

    protected function supports(string $attribute, $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::VIEW, self::EDIT])
            && $subject instanceof BlogPost;
    }

    protected function voteOnAttribute(string $attribute, $post, TokenInterface $token): bool
    {
        $user = $token->getUser();

        return match($attribute) {
            self::VIEW => $this->canView($post),
            self::EDIT => $this->canEdit($post, $user),
            default => false,
        };
    }

    private function canView(BlogPost $post): bool
    {
        return $post->getStatus() === BlogPost::STATUS_ACTIVE;
    }

    private function canEdit(BlogPost $post, ?User $user): bool
    {
        return $post->getUser() === $user
            || $this->security->isGranted('ROLE_ADMIN');
    }
}
