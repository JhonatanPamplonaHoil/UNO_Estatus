<?php

declare(strict_types=1);

use frontend\models\ReporteFallaForm;
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\captcha\Captcha;

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var \frontend\models\ReporteFallaForm $model */

$this->title = 'Reportar una falla';
$this->params['breadcrumbs'][] = $this->title;

$htmlIcon = <<<HTML
{label}<div class="input-group custom-input-group"><span class="input-group-text custom-icon" aria-hidden="true">%s</span>{input}</div>{error}{hint}
HTML;
$labelOptions = ['class' => 'form-label fw-semibold text-secondary small mb-1'];
?>

<style>
.site-reporte-wrapper {
    min-height: 82vh;
}
.login-split-card {
    border-radius: 1.25rem !important;
    box-shadow: 0 20px 40px rgba(92, 6, 32, 0.2) !important;
    max-width: 960px;
    width: 100%;
}
.login-brand-panel {
    background: linear-gradient(135deg, #4a041a 0%, #7d0d30 50%, #9e133c 100%);
    position: relative;
    overflow: hidden;
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
}
.custom-input-group .form-control,
.custom-input-group .form-select,
.reporte-textarea {
    border-color: #e2d1d6;
    background-color: #fcf8f9;
    padding: 0.65rem 0.9rem;
    font-size: 0.95rem;
    color: #2b030f;
}
.custom-input-group .form-control:focus,
.custom-input-group .form-select:focus,
.reporte-textarea:focus {
    border-color: #7d0d30;
    background-color: #ffffff;
    box-shadow: 0 0 0 4px rgba(125, 13, 48, 0.15);
}
.login-btn {
    background: linear-gradient(135deg, #7d0d30 0%, #5c0620 100%);
    border: none;
    padding: 0.75rem 1.5rem;
    font-weight: 600;
    box-shadow: 0 4px 14px rgba(125, 13, 48, 0.35);
    transition: all 0.25s ease;
}
.login-btn:hover {
    background: linear-gradient(135deg, #9e133c 0%, #7d0d30 100%);
    transform: translateY(-2px);
}
</style>

<div class="site-reportar-falla site-reporte-wrapper d-flex align-items-center justify-content-center py-4">
    <div class="card border-0 overflow-hidden login-split-card">
        <div class="row g-0">

            <!-- Panel Izquierdo: UNO Soporte -->
            <div class="col-md-4 d-none d-md-flex login-brand-panel text-white p-4 p-lg-5 flex-column justify-content-between">
                <div class="mb-4">
                    <div class="logo-uno-card">
                        <?= Html::img(
                            Yii::getAlias('@web/images/logo_uno.png'),
                            [
                                'alt' => 'UNO Universidad de Oriente',
                                'class' => 'img-fluid',
                                'style' => 'max-height: 48px;',
                            ]
                        ) ?>
                    </div>
                </div>

                <div>
                    <h2 class="fw-bold mb-3">Soporte Técnico</h2>
                    <p class="opacity-75 small mb-0" style="line-height: 1.6;">
                        ¿Encontraste un error, una página que no carga bien o algún problema con tu trámite? Repórtalo aquí para que podamos solucionarlo.
                    </p>
                </div>

                <div class="opacity-50 small mt-4">
                    Universidad de Oriente • Valladolid, Yucatán
                </div>
            </div>

            <!-- Panel Derecho: Formulario de Reporte -->
            <div class="col-md-8 bg-white p-4 p-lg-5">
                <div class="text-center mb-4">
                    <h1 class="h3 fw-bold mb-1 text-dark"><?= Html::encode($this->title) ?></h1>
                    <p class="text-muted small">Cuéntanos qué ocurrió y en qué sección del sistema estabas</p>
                </div>

                <?php $form = ActiveForm::begin(['id' => 'reporte-falla-form']); ?>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <?= $form->field($model, 'name', [
                            'options' => ['class' => 'mb-0'],
                            'template' => sprintf($htmlIcon, '👤'),
                            'inputOptions' => [
                                'class' => 'form-control',
                                'placeholder' => 'Tu nombre completo',
                            ],
                        ])->textInput()->label('Tu nombre', $labelOptions) ?>
                    </div>

                    <div class="col-md-6">
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
                </div>

                <div class="mb-3">
                    <?= $form->field($model, 'subject', [
                        'options' => ['class' => 'mb-0'],
                        'template' => sprintf($htmlIcon, '🛠️'),
                    ])->dropDownList(
                        ReporteFallaForm::getCategoriasFalla(),
                        [
                            'prompt' => 'Selecciona el tipo de falla...',
                            'class' => 'form-select',
                        ]
                    )->label('Tipo de falla', $labelOptions) ?>
                </div>

                <div class="mb-3">
                    <?= $form->field($model, 'body', [
                        'options' => ['class' => 'mb-0'],
                    ])->textarea([
                        'rows' => 4,
                        'class' => 'form-control reporte-textarea rounded-3',
                        'placeholder' => 'Describe detalladamente qué error apareció, qué botón presionaste o qué pantalla no cargó...',
                    ])->label('Descripción del problema', $labelOptions) ?>
                </div>

                <div class="row align-items-end g-3 mt-2">
                    <div class="col-md-8">
                        <?= $form->field($model, 'verifyCode', [
                            'options' => ['class' => 'mb-0'],
                        ])->widget(Captcha::class, [
                            'template' => '<div class="d-flex align-items-center gap-2"><div class="border rounded-2 p-1 bg-light">{image}</div><div class="flex-grow-1">{input}</div></div>',
                            'options' => [
                                'class' => 'form-control',
                                'placeholder' => 'Escribe el código',
                            ],
                        ])->label('Código de verificación', $labelOptions) ?>
                    </div>

                    <div class="col-md-4 d-grid">
                        <?= Html::submitButton(
                            'Enviar reporte',
                            [
                                'class' => 'btn login-btn rounded-3 text-white',
                                'name' => 'reporte-falla-button',
                            ]
                        ) ?>
                    </div>
                </div>

                <?php ActiveForm::end(); ?>
            </div>

        </div>
    </div>
</div>