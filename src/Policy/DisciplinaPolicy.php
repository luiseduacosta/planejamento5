<?php
declare(strict_types=1);

namespace App\Policy;

use App\Model\Entity\Disciplina;
use Authorization\IdentityInterface;

class DisciplinaPolicy
{
    public function canIndex(IdentityInterface $user): bool
    {
        return true;
    }

    public function canView(IdentityInterface $user, Disciplina $disciplina): bool
    {
        return true;
    }

    public function canAdd(IdentityInterface $user): bool
    {
        return in_array($user->role, ['admin', 'editor']);
    }

    public function canEdit(IdentityInterface $user, Disciplina $disciplina): bool
    {
        return in_array($user->role, ['admin', 'editor']);
    }

    public function canDelete(IdentityInterface $user, Disciplina $disciplina): bool
    {
        return $user->role === 'admin';
    }
}
