<?php
declare(strict_types=1);

namespace App\Policy;

use App\Model\Entity\Sala;
use Authorization\IdentityInterface;

class SalaPolicy
{
    public function canIndex(IdentityInterface $user): bool
    {
        return true;
    }

    public function canView(IdentityInterface $user, Sala $sala): bool
    {
        return true;
    }

    public function canAdd(IdentityInterface $user): bool
    {
        return in_array($user->role, ['admin', 'editor']);
    }

    public function canEdit(IdentityInterface $user, Sala $sala): bool
    {
        return in_array($user->role, ['admin', 'editor']);
    }

    public function canDelete(IdentityInterface $user, Sala $sala): bool
    {
        return $user->role === 'admin';
    }
}
