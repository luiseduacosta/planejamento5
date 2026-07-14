<?php
declare(strict_types=1);

namespace App\Policy;

use App\Model\Entity\Ementa;
use Authorization\IdentityInterface;

class EmentaPolicy
{
    public function canIndex(IdentityInterface $user): bool
    {
        return true;
    }

    public function canView(IdentityInterface $user, Ementa $ementa): bool
    {
        return true;
    }

    public function canAdd(IdentityInterface $user): bool
    {
        return in_array($user->role, ['admin', 'editor']);
    }

    public function canEdit(IdentityInterface $user, Ementa $ementa): bool
    {
        return in_array($user->role, ['admin', 'editor']);
    }

    public function canDelete(IdentityInterface $user, Ementa $ementa): bool
    {
        return $user->role === 'admin';
    }
}
