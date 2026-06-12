<?php
declare(strict_types=1);

namespace App\Policy;

use App\Model\Entity\User;
use Authorization\IdentityInterface;

class UserPolicy
{
    // Only admin can manage users
    public function canIndex(IdentityInterface $user): bool
    {
        return $user->role === 'admin';
    }

    public function canView(IdentityInterface $user, User $userEntity): bool
    {
        return $user->role === 'admin' || $user->id === $userEntity->id;
    }

    public function canAdd(IdentityInterface $user): bool
    {
        return $user->role === 'admin';
    }

    public function canEdit(IdentityInterface $user, User $userEntity): bool
    {
        return $user->role === 'admin' || $user->id === $userEntity->id;
    }

    public function canDelete(IdentityInterface $user, User $userEntity): bool
    {
        // Can't delete yourself
        return $user->role === 'admin' && $user->id !== $userEntity->id;
    }
}
