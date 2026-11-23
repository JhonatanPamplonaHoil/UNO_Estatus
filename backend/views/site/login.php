<?php

declare(strict_types=1);

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var \common\models\LoginForm $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

$this->title = 'Iniciar sesión';
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
                <div>
                    <?= Html::img(
                        Yii::getAlias('@web/images/yii3_full_white_for_dark.svg'),
                        [
                            'alt' => 'UNO Administración',
                            'class' => 'mb-4',
                            'height' => 40,
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
                    <div class="d-md-none mb-3">
                        <?= Html::img(
                            Yii::getAlias('@web/images/yii3_full_black_for_light.svg'),
                            [
                                'alt' => 'UNO Administración',
                                'class' => 'login-mobile-logo',
                                'height' => 36,
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