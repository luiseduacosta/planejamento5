<div class="row justify-content-center mt-5">
    <div class="col-md-6 col-lg-4">
        <div class="card shadow">
            <div class="card-header bg-primary text-white text-center">
                <h4 class="mb-0">
                    <i class="bi bi-box-arrow-in-right"></i> Login
                </h4>
                <small>Sistema de Planejamento ESS/UFRJ</small>
            </div>
            <div class="card-body p-4">
                <?= $this->Form->create(null, ['class' => 'needs-validation']) ?>
                
                <div class="mb-3">
                    <?= $this->Form->control('email', [
                        'class' => 'form-control form-control-lg',
                        'label' => ['text' => 'E-mail', 'class' => 'form-label'],
                        'placeholder' => 'Digite seu e-mail',
                        'required' => true,
                        'autocomplete' => 'email'
                    ]) ?>
                </div>
                
                <div class="mb-4">
                    <?= $this->Form->control('password', [
                        'class' => 'form-control form-control-lg',
                        'label' => ['text' => 'Senha', 'class' => 'form-label'],
                        'placeholder' => 'Digite sua senha',
                        'required' => true,
                        'autocomplete' => 'current-password'
                    ]) ?>
                </div>
                
                <?= $this->Form->button(__('Entrar'), [
                    'class' => 'btn btn-primary btn-lg w-100'
                ]) ?>
                
                <?= $this->Form->end() ?>
            </div>
            <div class="card-footer text-center text-muted">
                <small>
                    <i class="bi bi-shield-lock"></i> 
                    Acesso restrito - Faça login para continuar
                </small>
            </div>
        </div>
        
        <!-- Demo credentials info -->
        <div class="alert alert-info mt-3">
            <h6 class="alert-heading"><i class="bi bi-info-circle"></i> Credenciais de Teste:</h6>
            <hr>
            <p class="mb-1"><strong>Admin:</strong> admin / admin123</p>
            <p class="mb-0"><strong>Editor:</strong> editor / editor123</p>
        </div>
    </div>
</div>
