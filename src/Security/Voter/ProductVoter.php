<?php

namespace App\Security\Voter;

use App\Entity\Product;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class ProductVoter extends Voter
{
    const VIEW = 'view';
    const EDIT = 'edit';
    const DELETE = 'delete';
    const CREATE = 'create';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::VIEW, self::EDIT, self::DELETE, self::CREATE])
            && ($subject instanceof Product || $subject === null);
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        if (!$user instanceof User) {
            return false;
        }

        // Les utilisateurs peuvent voir les produits mais pas les modifier
        if ($attribute === self::VIEW) {
            return true;
        }

        // Les admins peuvent tout faire donc on leur donne accès
        if (in_array('ROLE_ADMIN', $user->getRoles())) {
            return true;
        }

        if ($attribute === self::CREATE && $subject === null) {
            return in_array('ROLE_ADMIN', $user->getRoles());
        }

        return false;
    }
}
