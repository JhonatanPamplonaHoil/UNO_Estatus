<?php

declare(strict_types=1);

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var \common\models\LoginForm $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

$this->title = 'Iniciar sesión';
$this->params['breadcrumbs'][] = $this->title;
$this->params['meta_description'] = 'Inicia sesión para acceder al Sistema UNO.';
$this->params['meta_keywords'] = 'uno, yii2, login, iniciar sesion, autenticacion, universidad de oriente';

$htmlIcon = <<<HTML
{label}<div class="input-group custom-input-group"><span class="input-group-text custom-icon" aria-hidden="true">%s</span>{input}</div>{error}{hint}
HTML;
$labelOptions = ['class' => 'form-label fw-semibold text-secondary small mb-1'];
?>

<style>
/* Estilos Institucionales UNO (Vino / Guinda) */
.site-login-wrapper {
    min-height: 85vh;
}

.login-split-card {
    border-radius: 1.25rem !important;
    box-shadow: 0 20px 40px rgba(92, 6, 32, 0.2) !important;
    max-width: 920px;
    width: 100%;
}

.login-brand-panel {
    background: linear-gradient(135deg, #4a041a 0%, #7d0d30 50%, #9e133c 100%);
    position: relative;
    overflow: hidden;
}

.login-brand-panel::before {
    content: '';
    position: absolute;
    top: -30%;
    right: -30%;
    width: 160%;
    height: 160%;
    background: radial-gradient(circle, rgba(255, 255, 255, 0.08) 0%, transparent 60%);
    pointer-events: none;
}

.login-brand-title {
    font-size: 2.1rem;
    line-height: 1.2;
    letter-spacing: -0.5px;
}

.logo-uno-card {
    background-color: #ffffff;
    border-radius: 0.75rem;
    padding: 0.75rem 1.25rem;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    display: inline-block;
}

.custom-input-group .custom-icon {
    background-color: #fcf8f9;
    border-color: #e2d1d6;
    color: #7d0d30;
    border-top-left-radius: 0.6rem;
    border-bottom-left-radius: 0.6rem;
    padding-left: 0.9rem;
    padding-right: 0.9rem;
}

.custom-input-group .form-control {
    border-color: #e2d1d6;
    background-color: #fcf8f9;
    border-top-right-radius: 0.6rem !important;
    border-bottom-right-radius: 0.6rem !important;
    padding: 0.65rem 0.9rem;
    font-size: 0.95rem;
    color: #2b030f;
}

.custom-input-group .form-control:focus {
    border-color: #7d0d30;
    background-color: #ffffff;
    box-shadow: 0 0 0 4px rgba(125, 13, 48, 0.15);
}

.login-btn {
    background: linear-gradient(135deg, #7d0d30 0%, #5c0620 100%);
    border: none;
    padding: 0.75rem 1.5rem;
    font-weight: 600;
    letter-spacing: 0.3px;
    box-shadow: 0 4px 14px rgba(125, 13, 48, 0.35);
    transition: all 0.25s ease;
}

.login-btn:hover {
    background: linear-gradient(135deg, #9e133c 0%, #7d0d30 100%);
    transform: translateY(-2px);
    box-shadow: 0 6px 18px rgba(125, 13, 48, 0.45);
}

.login-btn:active {
    transform: translateY(0);
}

.text-uno-primary {
    color: #7d0d30 !important;
}

.form-check-input:checked {
    background-color: #7d0d30;
    border-color: #7d0d30;
}
</style>

<div class="site-login site-login-wrapper d-flex align-items-center justify-content-center py-5">
    <div class="card border-0 overflow-hidden login-split-card">
        <div class="row g-0">

            <!-- Brand panel -->
            <div class="col-md-5 d-none d-md-flex login-brand-panel text-white">
                <div class="d-flex flex-column justify-content-between p-4 p-lg-5 w-100 position-relative" style="z-index: 1;">
                    <div class="mb-4">
                        <div class="logo-uno-card">
                            <?= Html::img(
                                Yii::getAlias('@web/images/logo_uno.png'),
                                [
                                    'alt' => 'Sistema UNO',
                                    'class' => 'img-fluid',
                                    'style' => 'max-height: 48px;',
                                    'onerror' => "this.style.display='none'; this.nextElementSibling.style.display='block';"
                                ],
                            ) ?>
                            <span class="fw-bold text-dark fs-4" style="display:none; letter-spacing: 1px;">
                                8<span class="text-uno-primary">UNO</span>
                            </span>
                        </div>
                    </div>
                    <div>
                        <h2 class="fw-bold mb-3 login-brand-title">
                            ¡Bienvenido<br>de nuevo!
                        </h2>
                        <p class="opacity-75 mb-0 login-brand-text small" style="line-height: 1.5;">
                            Ingresa al Sistema UNO para consultar y darle seguimiento a tus trámites de titulación.
                        </p>
                    </div>
                    <div class="opacity-50 small mt-4">
                        Universidad de Oriente • Valladolid, Yucatán
                    </div>
                </div>
            </div>

            <!-- Form panel -->
            <div class="col-md-7 bg-white">
                <div class="p-4 p-lg-5">
                    <div class="text-center mb-4">
                        <!-- Mobile-only logo -->
                        <div class="d-md-none mb-3">
                            <span class="fw-bold fs-3 text-uno-primary">Sistema UNO</span>
                        </div>
                        <h1 class="h3 fw-bold mb-1 text-dark"><?= Html::encode($this->title) ?></h1>
                        <p class="text-body-secondary small">Ingresa tus credenciales para continuar</p>
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
                            'label' => 'Recordarme en este equipo'
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

                    <div class="text-body-secondary text-center mt-4 pt-2 border-top small">
                        <?= Html::a('¿Olvidaste tu contraseña?', ['site/request-password-reset'], ['class' => 'text-uno-primary text-decoration-none fw-semibold']) ?>
                        <span class="mx-1">&middot;</span>
                        <?= Html::a('Reenviar correo de verificación', ['site/resend-verification-email'], ['class' => 'text-secondary text-decoration-none']) ?>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>