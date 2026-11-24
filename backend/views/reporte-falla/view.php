<?php

declare(strict_types=1);

use common\models\ReporteFalla;
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

/** @var yii\web\View $this */
/** @var \common\models\ReporteFalla $model */

$this->title = 'Reporte #' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Reportes de Fallas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="reporte-falla-view">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 fw-bold mb-0"><?= Html::encode($this->title) ?></h1>
        <?= Html::a('← Volver al listado', ['index'], ['class' => 'btn btn-outline-secondary btn-sm']) ?>
    </div>

    <div class="row g-4">
        <!-- Detalles del reporte -->
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-header bg-white fw-bold py-3">Información del reporte</div>
                <div class="card-body">
                    <p class="mb-2"><strong>Reportado por:</strong> <?= Html::encode($model->nombre) ?></p>
                    <p class="mb-2"><strong>Correo:</strong> <?= Html::mailto($model->email) ?></p>
                    <p class="mb-2"><strong>Tipo de falla:</strong> <?= Html::encode($model->categoria) ?></p>
                    <p class="mb-3"><strong>Fecha:</strong> <?= Yii::$app->formatter->asDatetime($model->created_at, 'php:d/m/Y H:i') ?></p>

                    <hr>
                    <p class="fw-semibold mb-2">Descripción del problema:</p>
                    <div class="p-3 bg-light rounded-3 border">
                        <?= nl2br(Html::encode($model->descripcion)) ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Panel de seguimiento Admin / Superusuario -->
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white fw-bold py-3">Seguimiento técnico</div>
                <div class="card-body">
                    <?php $form = ActiveForm::begin(); ?>

                    <?= $form->field($model, 'estado')->dropDownList(ReporteFalla::getEstados(), [
                        'class' => 'form-select',
                    ]) ?>

                    <?= $form->field($model, 'notas_admin')->textarea([
                        'rows' => 4,
                        'placeholder' => 'Escribe qué se revisó o cómo se solucionó la falla...',
                    ]) ?>

                    <div class="d-grid mt-3">
                        <?= Html::submitButton('Guardar cambios', ['class' => 'btn btn-primary']) ?>
                    </div>

                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>