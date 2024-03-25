<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class TripVoter extends Voter
{
    public const EDIT = 'TRIP_EDIT';
    public const OWNER = 'TRIP_OWNER';

    protected function supports(string $attribute, mixed $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::EDIT])
            && $subject instanceof \App\Entity\Trip;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::EDIT:
                // logic to determine if the user can EDIT
                if ($user === $subject->getMember()) return true;
                // return true or false
                break;
            case self::EDIT:
                // logic to determine if the user can EDIT
                if ($user === $subject->getMember()) return true;
                break;
        }

        return false;
    }
}
