<?php
declare(strict_types=1);

namespace App\Policy;

use App\Model\Entity\Optativa;
use Authorization\IdentityInterface;

class OptativaPolicy
{
    public function canIndex(IdentityInterface $user): bool
    {
        return true;
    }

    public function canView(IdentityInterface $user, Optativa $optativa): bool
    {
        return true;
    }

    public function canAdd(IdentityInterface $user): bool
    {
        return in_array($user->role, ['admin', 'editor']);
    }

    public function canEdit(IdentityInterface $user, Optativa $optativa): bool
    {
        return in_array($user->role, ['admin', 'editor']);
    }

    public function canDelete(IdentityInterface $user, Optativa $optativa): bool
    {
        return $user->role === 'admin';
    }
}
