<?php

declare(strict_types=1);

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var \frontend\models\ResetPasswordForm $model */

$this->title = 'Nueva contraseña';
$this->params['breadcrumbs'][] = $this->title;

$labelOptions = ['class' => 'form-label fw-semibold text-secondary small mb-1'];
?>

<style>
.auth-wrapper { min-height: 80vh; }
.login-split-card { border-radius: 1.25rem !important; box-shadow: 0 20px 40px rgba(92, 6, 32, 0.2) !important; max-width: 860px; width: 100%; }
.login-brand-panel { background: linear-gradient(135deg, #4a041a 0%, #7d0d30 50%, #9e133c 100%); }
.logo-uno-card { background-color: #ffffff; border-radius: 0.75rem; padding: 0.75rem 1.25rem; display: inline-block; }
.custom-input-group .custom-icon { background-color: #fcf8f9; border-color: #e2d1d6; color: #7d0d30; }
.custom-input-group .form-control { border-color: #e2d1d6; background-color: #fcf8f9; padding: 0.65rem 0.9rem; }
.custom-input-group .form-control:focus { border-color: #7d0d30; background-color: #ffffff; box-shadow: 0 0 0 4px rgba(125, 13, 48, 0.15); }
.login-btn { background: linear-gradient(135deg, #7d0d30 0%, #5c0620 100%); border: none; padding: 0.75rem 1.5rem; font-weight: 600; }
.login-btn:hover { background: linear-gradient(135deg, #9e133c 0%, #7d0d30 100%); }
</style>

<div class="auth-wrapper d-flex align-items-center justify-content-center py-5">
    <div class="card border-0 overflow-hidden login-split-card">
        <div class="row g-0">
            <div class="col-md-5 d-none d-md-flex login-brand-panel text-white p-4 p-lg-5 flex-column justify-content-between">
                <div class="logo-uno-card">
                    <?= Html::img(Yii::getAlias('@web/images/logo_uno.png'), ['alt' => 'UNO', 'class' => 'img-fluid', 'style' => 'max-height: 48px;']) ?>
                </div>
                <div>
                    <h2 class="fw-bold mb-3">Actualiza tu contraseña</h2>
                    <p class="opacity-75 small mb-0">Elige una nueva contraseña segura y confírmala para volver a acceder a tu cuenta.</p>
                </div>
                <div class="opacity-50 small">Universidad de Oriente • Valladolid, Yucatán</div>
            </div>

            <div class="col-md-7 bg-white p-4 p-lg-5">
                <div class="text-center mb-4">
                    <h1 class="h3 fw-bold mb-1 text-dark"><?= Html::encode($this->title) ?></h1>
                    <p class="text-muted small">Ingresa y confirma tu nueva contraseña</p>
                </div>

                <?php $form = ActiveForm::begin(['id' => 'reset-password-form']); ?>

                <div class="mb-3">
                    <?= $form->field($model, 'password', [
                        'options' => ['class' => 'mb-0'],
                        'template' => '{label}<div class="input-group custom-input-group"><span class="input-group-text custom-icon">🔒</span>{input}<span class="input-group-text bg-white d-none pass-status-icon"></span></div>{error}{hint}',
                        'inputOptions' => [
                            'class' => 'form-control',
                            'placeholder' => '••••••••',
                            'autofocus' => true,
                        ],
                    ])->passwordInput()->label('Contraseña', $labelOptions) ?>
                </div>

                <div class="mb-4">
                    <?= $form->field($model, 'password_repeat', [
                        'options' => ['class' => 'mb-0'],
                        'template' => '{label}<div class="input-group custom-input-group"><span class="input-group-text custom-icon">🔑</span>{input}<span class="input-group-text bg-white d-none pass-status-icon"></span></div>{error}{hint}',
                        'inputOptions' => [
                            'class' => 'form-control',
                            'placeholder' => 'Repite tu contraseña',
                        ],
                    ])->passwordInput()->label('Confirmar contraseña', $labelOptions) ?>
                </div>

                <div class="d-grid">
                    <?= Html::submitButton('Guardar nueva contraseña', ['class' => 'btn login-btn btn-lg rounded-3 text-white']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>

<?php
$this->registerJs(<<<JS
const passInput = $('#resetpasswordform-password');
const repeatInput = $('#resetpasswordform-password_repeat');
const statusIcons = $('.pass-status-icon');
const repeatError = repeatInput.closest('.field-resetpasswordform-password_repeat').find('.invalid-feedback');

function validarCoincidencia() {
    const pass = passInput.val();
    const repeat = repeatInput.val();

    if (repeat.length === 0) {
        statusIcons.addClass('d-none').text('');
        return;
    }

    statusIcons.removeClass('d-none');

    if (pass === repeat && pass.length > 0) {
        statusIcons.text('✅');
        repeatInput.removeClass('is-invalid').addClass('is-valid');
        passInput.removeClass('is-invalid').addClass('is-valid');
        repeatError.text('').hide();
    } else {
        statusIcons.text('❌');
        repeatInput.removeClass('is-valid').addClass('is-invalid');
        repeatError.text('Las contraseñas no coinciden.').show();
    }
}

passInput.on('input', validarCoincidencia);
repeatInput.on('input', validarCoincidencia);
JS);
?>