<?php
declare(strict_types=1);

namespace App\Policy;

use App\Model\Entity\Docente;
use Authorization\IdentityInterface;

class DocentePolicy
{
    public function canIndex(IdentityInterface $user): bool
    {
        return true;
    }

    public function canView(IdentityInterface $user, Docente $docente): bool
    {
        return true;
    }

    public function canAdd(IdentityInterface $user): bool
    {
        return in_array($user->role, ['admin', 'editor']);
    }

    public function canEdit(IdentityInterface $user, Docente $docente): bool
    {
        return in_array($user->role, ['admin', 'editor']);
    }

    public function canDelete(IdentityInterface $user, Docente $docente): bool
    {
        return $user->role === 'admin';
    }
}
