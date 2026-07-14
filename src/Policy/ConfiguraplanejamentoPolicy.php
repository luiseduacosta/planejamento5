<?php
declare(strict_types=1);

namespace App\Policy;

use App\Model\Entity\Configuraplanejamento;
use Authorization\IdentityInterface;

class ConfiguraplanejamentoPolicy
{
    public function canIndex(IdentityInterface $user): bool
    {
        return true;
    }

    public function canView(IdentityInterface $user, Configuraplanejamento $config): bool
    {
        return true;
    }

    public function canAdd(IdentityInterface $user): bool
    {
        return $user !== null;
    }

    // User must be owner or admin
    public function canEdit(IdentityInterface $user, Configuraplanejamento $config): bool
    {
        return $user->role === 'admin' || $config->usuarioplanejamento_id === $user->id;
    }

    // Only admin can delete, and only if no planejamentos associated
    public function canDelete(IdentityInterface $user, Configuraplanejamento $config): bool
    {
        return $user->role === 'admin';
    }

    // Only admin can clone
    public function canClone(IdentityInterface $user): bool
    {
        return $user->role === 'admin';
    }
}
