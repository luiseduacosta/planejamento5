<?php
declare(strict_types=1);

namespace App\Policy;

use App\Model\Entity\Planejamento;
use Authorization\IdentityInterface;

class PlanejamentoPolicy
{
    public function canIndex(IdentityInterface $user): bool
    {
        return true;
    }

    public function canView(IdentityInterface $user, Planejamento $planejamento): bool
    {
        return true;
    }

    public function canAdd(IdentityInterface $user): bool
    {
        return $user !== null;
    }

    public function canEdit(IdentityInterface $user, Planejamento $planejamento): bool
    {
        return in_array($user->role, ['admin', 'editor']);
    }

    public function canDelete(IdentityInterface $user, Planejamento $planejamento): bool
    {
        return $user->role === 'admin';
    }

    public function canClone(IdentityInterface $user): bool
    {
        return $user->role === 'admin';
    }

    public function canCreateVersion(IdentityInterface $user): bool
    {
        return in_array($user->role, ['admin', 'editor']);
    }
}
