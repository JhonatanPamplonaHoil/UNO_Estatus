<?php

declare(strict_types=1);

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var \frontend\models\VerifyResetCodeForm $model */
/** @var int $remainingSeconds */

$this->title = 'Verificar clave';
$this->params['breadcrumbs'][] = $this->title;

$labelOptions = ['class' => 'form-label fw-semibold text-secondary small mb-1'];
?>

<style>
.auth-wrapper { min-height: 80vh; }
.login-split-card { border-radius: 1.25rem !important; box-shadow: 0 20px 40px rgba(92, 6, 32, 0.2) !important; max-width: 860px; width: 100%; }
.login-brand-panel { background: linear-gradient(135deg, #4a041a 0%, #7d0d30 50%, #9e133c 100%); }
.logo-uno-card { background-color: #ffffff; border-radius: 0.75rem; padding: 0.75rem 1.25rem; display: inline-block; }
.custom-input-group .custom-icon { background-color: #fcf8f9; border-color: #e2d1d6; color: #7d0d30; }
.custom-input-group .form-control { border-color: #e2d1d6; background-color: #fcf8f9; padding: 0.65rem 0.9rem; letter-spacing: 4px; font-weight: 700; text-align: center; font-size: 1.2rem; }
.custom-input-group .form-control:focus { border-color: #7d0d30; background-color: #ffffff; box-shadow: 0 0 0 4px rgba(125, 13, 48, 0.15); }
.login-btn { background: linear-gradient(135deg, #7d0d30 0%, #5c0620 100%); border: none; padding: 0.75rem 1.5rem; font-weight: 600; }
.login-btn:hover { background: linear-gradient(135deg, #9e133c 0%, #7d0d30 100%); }
.btn-uno-outline { border: 1.5px solid #7d0d30; color: #7d0d30; font-weight: 600; background-color: transparent; transition: all 0.2s ease; }
.btn-uno-outline:hover:not(:disabled) { background-color: #7d0d30; color: #ffffff; }
.btn-uno-outline:disabled { border-color: #cbd5e1; color: #94a3b8; cursor: not-allowed; }
.text-uno-primary { color: #7d0d30 !important; }
</style>

<div class="auth-wrapper d-flex align-items-center justify-content-center py-5">
    <div class="card border-0 overflow-hidden login-split-card">
        <div class="row g-0">
            <div class="col-md-5 d-none d-md-flex login-brand-panel text-white p-4 p-lg-5 flex-column justify-content-between">
                <div class="logo-uno-card">
                    <?= Html::img(Yii::getAlias('@web/images/logo_uno.png'), ['alt' => 'UNO', 'class' => 'img-fluid', 'style' => 'max-height: 48px;']) ?>
                </div>
                <div>
                    <h2 class="fw-bold mb-3">Revisa tu correo</h2>
                    <p class="opacity-75 small mb-2">Enviamos una clave de 6 dígitos a <b><?= Html::encode($model->email) ?></b>.</p>
                    <p class="opacity-75 small mb-0">⏳ La clave tiene una vigencia de <b>30 minutos</b>.</p>
                </div>
                <div class="opacity-50 small">Universidad de Oriente • Valladolid, Yucatán</div>
            </div>

            <div class="col-md-7 bg-white p-4 p-lg-5">
                <div class="text-center mb-4">
                    <h1 class="h3 fw-bold mb-1 text-dark"><?= Html::encode($this->title) ?></h1>
                    <p class="text-muted small">Ingresa la clave numérica que recibiste</p>
                </div>

                <?php $form = ActiveForm::begin(['id' => 'verify-reset-code-form']); ?>

                <div class="mb-4">
                    <?= $form->field($model, 'code', [
                        'options' => ['class' => 'mb-0'],
                        'template' => '{label}<div class="input-group custom-input-group"><span class="input-group-text custom-icon">🔢</span>{input}</div>{error}{hint}',
                        'inputOptions' => [
                            'class' => 'form-control',
                            'placeholder' => '000000',
                            'maxlength' => 6,
                            'autofocus' => true,
                        ],
                    ])->textInput()->label('Clave de verificación', $labelOptions) ?>
                </div>

                <div class="d-grid mb-3">
                    <?= Html::submitButton('Verificar clave', ['class' => 'btn login-btn btn-lg rounded-3 text-white']) ?>
                </div>

                <?php ActiveForm::end(); ?>

                <!-- Botón de reenvío con contador de 30 segundos -->
                <div class="text-center mt-3">
                    <p class="text-muted small mb-2">¿No te llegó el código o ya expiró?</p>
                    <?= Html::beginForm(['site/resend-reset-code'], 'post') ?>
                        <?= Html::submitButton(
                            $remainingSeconds > 0 ? "Reenviar código ({$remainingSeconds}s)" : '🔄 Reenviar código',
                            [
                                'id' => 'btn-resend-code',
                                'class' => 'btn btn-uno-outline btn-sm rounded-3 px-4 py-2',
                                'disabled' => $remainingSeconds > 0,
                            ]
                        ) ?>
                    <?= Html::endForm() ?>
                </div>

                <div class="text-center mt-4 pt-3 border-top small">
                    <?= Html::a('← Volver a ingresar correo', ['site/request-password-reset'], ['class' => 'fw-bold text-uno-primary text-decoration-none']) ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$seconds = (int) $remainingSeconds;
$this->registerJs(<<<JS
let segundos = {$seconds};
const btnResend = document.getElementById('btn-resend-code');

if (btnResend && segundos > 0) {
    const intervalo = setInterval(() => {
        segundos--;
        if (segundos <= 0) {
            clearInterval(intervalo);
            btnResend.disabled = false;
            btnResend.textContent = '🔄 Reenviar código';
        } else {
            btnResend.textContent = 'Reenviar código (' + segundos + 's)';
        }
    }, 1000);
}
JS);
?>