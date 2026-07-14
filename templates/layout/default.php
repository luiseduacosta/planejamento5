<?php
/**
 * Default Layout with Bootstrap 5 Navigation Menu
 */

$cakeDescription = 'Planejamento5';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>
        <?= $cakeDescription ?>:
        <?= $this->fetch('title') ?>
    </title>
    <?= $this->Html->meta('icon') ?>

    <!-- Bootstrap 5 CSS -->
    <?= $this->Html->css('https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css') ?>
    
    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <?= $this->Html->link('Planejamento5', '/', ['class' => 'navbar-brand']) ?>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <!-- Configurações -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Configurações</a>
                        <ul class="dropdown-menu">
                            <li><?= $this->Html->link(__('Semestres'), ['controller' => 'Configuraplanejamentos', 'action' => 'index'], ['class' => 'dropdown-item']) ?></li>
                            <li><?= $this->Html->link(__('Usuários'), ['controller' => 'Usuarioplanejamentos', 'action' => 'index'], ['class' => 'dropdown-item']) ?></li>
                        </ul>
                    </li>
                    
                    <!-- Cadastros -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Cadastros</a>
                        <ul class="dropdown-menu">
                            <li><?= $this->Html->link(__('Docentes'), ['controller' => 'Docentes', 'action' => 'index'], ['class' => 'dropdown-item']) ?></li>
                            <li><?= $this->Html->link(__('Disciplinas'), ['controller' => 'Disciplinas', 'action' => 'index'], ['class' => 'dropdown-item']) ?></li>
                            <li><?= $this->Html->link(__('Salas'), ['controller' => 'Salas', 'action' => 'index'], ['class' => 'dropdown-item']) ?></li>
                            <li><?= $this->Html->link(__('Optativas'), ['controller' => 'Optativas', 'action' => 'index'], ['class' => 'dropdown-item']) ?></li>
                        </ul>
                    </li>
                    
                    <!-- Horários -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Horários</a>
                        <ul class="dropdown-menu">
                            <li><?= $this->Html->link(__('Dias'), ['controller' => 'Dias', 'action' => 'index'], ['class' => 'dropdown-item']) ?></li>
                            <li><?= $this->Html->link(__('Horários'), ['controller' => 'Horarios', 'action' => 'index'], ['class' => 'dropdown-item']) ?></li>
                        </ul>
                    </li>
                    
                    <!-- Acadêmico -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Acadêmico</a>
                        <ul class="dropdown-menu">
                            <li><?= $this->Html->link(__('Ementas'), ['controller' => 'Ementas', 'action' => 'index'], ['class' => 'dropdown-item']) ?></li>
                        </ul>
                    </li>
                    
                    <!-- Planejamentos -->
                    <li class="nav-item">
                        <?= $this->Html->link('Planejamentos', ['controller' => 'Planejamentos', 'action' => 'index'], ['class' => 'nav-link']) ?>
                    </li>
                </ul>
                
                <!-- User Menu -->
                <ul class="navbar-nav">
                    <?php
                    $user = $this->request->getAttribute('identity');
                    if ($user):
                    ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <?= h($user->username) ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><?= $this->Html->link('Perfil', ['controller' => 'Usuarioplanejamentos', 'action' => 'view', $user->id], ['class' => 'dropdown-item']) ?></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><?= $this->Html->link('Sair', ['controller' => 'Usuarioplanejamentos', 'action' => 'logout'], ['class' => 'dropdown-item']) ?></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <?= $this->Html->link('Login', ['controller' => 'Usuarioplanejamentos', 'action' => 'login'], ['class' => 'nav-link']) ?>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
    
    <!-- Main Content -->
    <main class="py-4">
        <div class="container">
            <?= $this->Flash->render() ?>
            <?= $this->fetch('content') ?>
        </div>
    </main>
    
    <!-- Footer -->
    <footer class="bg-light py-3 mt-5">
        <div class="container text-center">
            <p class="text-muted mb-0">&copy; <?= date('Y') ?> Planejamento5 - Sistema de Planejamento Acadêmico</p>
        </div>
    </footer>
    
    <!-- Bootstrap 5 JS -->
    <?= $this->Html->script('https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js') ?>
</body>
</html>
