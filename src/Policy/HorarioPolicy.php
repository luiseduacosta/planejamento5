<?php
declare(strict_types=1);

namespace App\Policy;

use App\Model\Entity\Horario;
use Authorization\IdentityInterface;

class HorarioPolicy
{
    public function canIndex(IdentityInterface $user): bool
    {
        return true;
    }

    public function canView(IdentityInterface $user, Horario $horario): bool
    {
        return true;
    }

    public function canAdd(IdentityInterface $user): bool
    {
        return $user->role === 'admin';
    }

    public function canEdit(IdentityInterface $user, Horario $horario): bool
    {
        return $user->role === 'admin';
    }

    public function canDelete(IdentityInterface $user, Horario $horario): bool
    {
        return $user->role === 'admin';
    }
}
