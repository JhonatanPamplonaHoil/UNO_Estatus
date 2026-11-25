<?php

declare(strict_types=1);

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var \common\models\LoginForm $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

$this->title = 'Iniciar sesión';

$bgUrl = Yii::getAlias('@web/images/colorvino.png');
$this->registerCss(<<<CSS
body {
    background-image: url('{$bgUrl}') !important;
    background-size: cover !important;
    background-position: center center !important;
    background-repeat: no-repeat !important;
    background-attachment: fixed !important;
    min-height: 100vh;
}

.login-split-card {
    --bs-card-bg: transparent !important;
    background: rgba(110, 18, 34, 0.34) !important;
    backdrop-filter: blur(8px) saturate(135%) !important;
    -webkit-backdrop-filter: blur(8px) saturate(135%) !important;
    border: 1px solid rgba(255, 255, 255, 0.45) !important;
    box-shadow: 0 10px 34px rgba(58, 6, 16, 0.45), inset 0 1px 1px rgba(255, 255, 255, 0.55) !important;
}

.login-brand-panel {
    background: rgba(88, 12, 26, 0.46) !important;
    background-image: none !important;
    border-right: 1px solid rgba(255, 255, 255, 0.28) !important;
}

.login-logo-badge {
    background: rgba(255, 255, 255, 0.88);
    border-radius: 14px;
    padding: 8px 14px;
    box-shadow: 0 4px 14px rgba(0, 0, 0, 0.22);
}

.login-admin-icon {
    filter: drop-shadow(0 6px 14px rgba(0, 0, 0, 0.45));
}

/* Textos en blanco puro con sombra sutil para máxima lectura */
.login-split-card h1,
.login-split-card h2,
.login-split-card .form-label,
.login-split-card .form-check-label {
    color: #ffffff !important;
    text-shadow: 0 1px 4px rgba(0, 0, 0, 0.78) !important;
}

.login-split-card .text-body-secondary,
.login-split-card .login-brand-text {
    color: rgba(255, 255, 255, 0.94) !important;
    opacity: 1 !important;
    text-shadow: 0 1px 4px rgba(0, 0, 0, 0.78) !important;
}

/* Campos de entrada nítidos con enfoque en tono vino */
.login-split-card .form-control,
.login-split-card .input-group-text {
    background-color: rgba(255, 255, 255, 0.88) !important;
    color: #111827 !important;
    border: 1px solid rgba(255, 255, 255, 0.75) !important;
}

.login-split-card .form-control::placeholder {
    color: #4b5563 !important;
}

.login-split-card .form-control:focus {
    background-color: #ffffff !important;
    color: #111827 !important;
    border-color: #a41e37 !important;
    box-shadow: 0 0 0 0.22rem rgba(164, 30, 55, 0.35) !important;
}

.login-split-card .form-check-input:checked {
    background-color: #8b152b !important;
    border-color: #8b152b !important;
}

.login-split-card .form-check-input:focus {
    border-color: #a41e37 !important;
    box-shadow: 0 0 0 0.2rem rgba(164, 30, 55, 0.3) !important;
}

/* Botón Iniciar sesión en rojo vino */
.login-split-card .login-btn {
    background: linear-gradient(135deg, #8b152b 0%, #5c0b1a 100%) !important;
    border: 1px solid rgba(255, 255, 255, 0.35) !important;
    box-shadow: 0 4px 14px rgba(58, 6, 16, 0.4) !important;
}

.login-split-card .login-btn:hover,
.login-split-card .login-btn:focus {
    background: linear-gradient(135deg, #a31933 0%, #700d20 100%) !important;
    border-color: rgba(255, 255, 255, 0.55) !important;
}

/* Mensajes de error legibles sobre el cristal */
.login-split-card .invalid-feedback {
    color: #ffe4e6 !important;
    font-weight: 600 !important;
    text-shadow: 0 1px 3px rgba(0, 0, 0, 0.85) !important;
}
CSS);

$htmlIcon = <<<HTML
{label}<div class="input-group"><span class="input-group-text" aria-hidden="true">%s</span>{input}</div>{error}{hint}
HTML;
$labelOptions = ['class' => 'form-label fw-semibold small'];
?>
<div class="card border-0 overflow-hidden login-split-card">
    <div class="row g-0">

        <!-- Brand panel -->
        <div class="col-md-5 d-none d-md-flex login-brand-panel text-white">
            <div class="d-flex flex-column justify-content-between p-4 p-lg-5 w-100">
                <div class="text-center">
                    <?= Html::img(
                        Yii::getAlias('@web/images/logounova.png'),
                        [
                            'alt' => 'UNO Administración',
                            'class' => 'mb-3 mx-auto d-inline-block login-logo-badge',
                            'height' => 85,
                        ],
                    ) ?>
                </div>
                <div class="my-auto text-center py-2">
                    <?= Html::img(
                        Yii::getAlias('@web/images/useradmin.png'),
                        [
                            'alt' => 'Administrador UNO',
                            'class' => 'mx-auto d-block login-admin-icon',
                            'height' => 145,
                        ],
                    ) ?>
                </div>
                <div>
                    <h2 class="fw-bold mb-3 login-brand-title">
                        Panel<br>Administrativo
                    </h2>
                    <p class="opacity-75 mb-0 login-brand-text">
                        Gestiona los trámites de titulación, fechas disponibles y citas agendadas de la UNO.
                    </p>
                </div>
            </div>
        </div>

        <!-- Form panel -->
        <div class="col-md-7">
            <div class="p-4 p-lg-5">
                <div class="text-center mb-4">
                    <!-- Mobile-only logo -->
                    <div class="d-md-none mb-3 text-center">
                        <?= Html::img(
                            Yii::getAlias('@web/images/logounova.png'),
                            [
                                'alt' => 'UNO Administración',
                                'class' => 'login-mobile-logo mx-auto d-inline-block login-logo-badge',
                                'height' => 65,
                            ],
                        ) ?>
                    </div>
                    <h1 class="h3 fw-bold mb-1"><?= Html::encode($this->title) ?></h1>
                    <p class="text-body-secondary small">Ingresa tus credenciales para acceder al panel administrativo</p>
                </div>

                <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

                <div class="mb-3">
                    <?= $form->field($model, 'email', [
                        'options' => ['class' => 'mb-0'],
                        'template' => sprintf($htmlIcon, '✉️'),
                        'inputOptions' => [
                            'class' => 'form-control',
                            'type' => 'email',
                            'placeholder' => 'correo@ejemplo.com',
                            'autofocus' => true,
                        ],
                    ])->textInput()->label('Correo electrónico', $labelOptions) ?>
                </div>

                <div class="mb-3">
                    <?= $form->field($model, 'password', [
                        'options' => ['class' => 'mb-0'],
                        'template' => sprintf($htmlIcon, '🔒'),
                        'inputOptions' => [
                            'class' => 'form-control',
                            'placeholder' => '••••••••',
                        ],
                    ])->passwordInput()->label('Contraseña', $labelOptions) ?>
                </div>

                <div class="mb-4">
                    <?= $form->field($model, 'rememberMe')->checkbox([
                        'label' => 'Recordarme en este equipo',
                    ]) ?>
                </div>

                <div class="d-grid">
                    <?= Html::submitButton(
                        'Iniciar sesión',
                        [
                            'class' => 'btn login-btn btn-lg rounded-3 text-white',
                            'name' => 'login-button',
                        ],
                    ) ?>
                </div>

                <?php ActiveForm::end(); ?>

            </div>
        </div>

    </div>
</div>