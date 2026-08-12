<?php
declare(strict_types=1);

namespace App\Policy;

use App\Model\Entity\Turmaotp;
use Authorization\IdentityInterface;

class TurmaotpPolicy
{
    public function canIndex(IdentityInterface $user): bool
    {
        return true;
    }

    public function canView(IdentityInterface $user, Turmaotp $turmaotp): bool
    {
        return true;
    }

    public function canAdd(IdentityInterface $user): bool
    {
        return in_array($user->role, ['admin', 'editor']);
    }

    public function canEdit(IdentityInterface $user, Turmaotp $turmaotp): bool
    {
        return in_array($user->role, ['admin', 'editor']);
    }

    public function canDelete(IdentityInterface $user, Turmaotp $turmaotp): bool
    {
        return $user->role === 'admin';
    }
}
