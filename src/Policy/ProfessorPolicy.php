<?php
declare(strict_types=1);

namespace App\Policy;

use App\Model\Entity\Professor;
use Authorization\IdentityInterface;

class ProfessorPolicy
{
    public function canIndex(IdentityInterface $user): bool
    {
        return true;
    }

    public function canView(IdentityInterface $user, Professor $professor): bool
    {
        return true;
    }

    public function canAdd(IdentityInterface $user): bool
    {
        return in_array($user->role, ['admin', 'editor']);
    }

    public function canEdit(IdentityInterface $user, Professor $professor): bool
    {
        return in_array($user->role, ['admin', 'editor']);
    }

    public function canDelete(IdentityInterface $user, Professor $professor): bool
    {
        return $user->role === 'admin';
    }
}
