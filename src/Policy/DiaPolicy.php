<?php
declare(strict_types=1);

namespace App\Policy;

use App\Model\Entity\Dia;
use Authorization\IdentityInterface;

class DiaPolicy
{
    public function canIndex(IdentityInterface $user): bool
    {
        return true;
    }

    public function canView(IdentityInterface $user, Dia $dia): bool
    {
        return true;
    }

    public function canAdd(IdentityInterface $user): bool
    {
        return $user->role === 'admin';
    }

    public function canEdit(IdentityInterface $user, Dia $dia): bool
    {
        return $user->role === 'admin';
    }

    public function canDelete(IdentityInterface $user, Dia $dia): bool
    {
        return $user->role === 'admin';
    }
}
