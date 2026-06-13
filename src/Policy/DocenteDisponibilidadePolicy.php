<?php
declare(strict_types=1);

namespace App\Policy;

use App\Model\Entity\DocenteDisponibilidade;
use Authorization\IdentityInterface;

class DocenteDisponibilidadePolicy
{
    public function canIndex(IdentityInterface $user): bool
    {
        return true;
    }

    public function canView(IdentityInterface $user, DocenteDisponibilidade $docenteDisponibilidade): bool
    {
        return true;
    }

    public function canAdd(IdentityInterface $user): bool
    {
        return in_array($user->role, ['admin', 'editor']);
    }

    public function canEdit(IdentityInterface $user, DocenteDisponibilidade $docenteDisponibilidade): bool
    {
        return in_array($user->role, ['admin', 'editor']);
    }

    public function canDelete(IdentityInterface $user, DocenteDisponibilidade $docenteDisponibilidade): bool
    {
        return $user->role === 'admin';
    }
}

