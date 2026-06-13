<?php
declare(strict_types=1);

namespace App\Policy;

use App\Model\Entity\Usuarioplanejamento;
use Authorization\IdentityInterface;

class UsuarioplanejamentoPolicy
{
    public function canIndex(IdentityInterface $user): bool
    {
        return $user !== null;
    }

    public function canView(IdentityInterface $user, Usuarioplanejamento $usuarioplanejamento): bool
    {
        return $user->role === 'admin' || $user->id === $usuarioplanejamento->id;
    }

    public function canAdd(IdentityInterface $user): bool
    {
        return $user->role === 'admin';
    }

    public function canEdit(IdentityInterface $user, Usuarioplanejamento $usuarioplanejamento): bool
    {
        return $user->role === 'admin' || $user->id === $usuarioplanejamento->id;
    }

    public function canDelete(IdentityInterface $user, Usuarioplanejamento $usuarioplanejamento): bool
    {
        return $user->role === 'admin' && $user->id !== $usuarioplanejamento->id;
    }
}

