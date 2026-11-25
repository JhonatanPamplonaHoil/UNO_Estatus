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

<style>
    .reporte-falla-view .rfv-banner {
        background: linear-gradient(90deg, #6b0f1a 0%, #7d121f 100%);
        color: #ffffff;
        border-radius: 14px;
        padding: 20px 26px;
        box-shadow: 0 8px 20px rgba(107, 15, 26, 0.16);
    }

    .reporte-falla-view .rfv-banner-sub {
        color: rgba(255, 255, 255, 0.86);
        font-size: 0.9rem;
    }

    .reporte-falla-view .rfv-btn-volver {
        background-color: #ffffff;
        color: #4d0911;
        border: 1px solid rgba(255, 255, 255, 0.4);
        border-radius: 9px;
        font-weight: 600;
        transition: all 0.15s ease-in-out;
    }

    .reporte-falla-view .rfv-btn-volver:hover {
        background-color: #f4b400;
        border-color: #f4b400;
        color: #1f1600;
    }

    .reporte-falla-view .rfv-card {
        background-color: #ffffff;
        border: 1px solid rgba(107, 15, 26, 0.13);
        border-top: 4px solid #6b0f1a;
        border-radius: 14px;
        box-shadow: 0 10px 25px rgba(107, 15, 26, 0.08);
    }

    .reporte-falla-view .rfv-card-header {
        background-color: #f8f1f1 !important;
        color: #4a0e12;
        border-bottom: 1px solid rgba(107, 15, 26, 0.12) !important;
        font-size: 0.95rem;
        letter-spacing: 0.01em;
    }

    .reporte-falla-view .rfv-dato-label {
        color: #5c3a3d;
    }

    .reporte-falla-view .rfv-descripcion-box {
        background-color: #fcf8f8;
        border: 1px solid rgba(107, 15, 26, 0.14) !important;
        border-left: 3px solid #6b0f1a !important;
        color: #2b2122;
    }

    .reporte-falla-view .form-label {
        font-weight: 600;
        color: #4a0e12;
        font-size: 0.9rem;
    }

    .reporte-falla-view .form-select:focus,
    .reporte-falla-view .form-control:focus {
        border-color: #7d0d30;
        box-shadow: 0 0 0 0.2rem rgba(125, 13, 48, 0.16);
    }

    .reporte-falla-view .rfv-btn-guardar {
        background-color: #7d0d30;
        border-color: #7d0d30;
        border-radius: 9px;
        transition: all 0.15s ease-in-out;
    }

    .reporte-falla-view .rfv-btn-guardar:hover {
        background-color: #5c0923;
        border-color: #5c0923;
    }
</style>

<div class="reporte-falla-view py-2">
    <div class="rfv-banner d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-4 gap-3">
        <div>
            <h1 class="h3 fw-bold mb-1"><?= Html::encode($this->title) ?></h1>
            <div class="rfv-banner-sub">Detalle del reporte y actualización de seguimiento técnico.</div>
        </div>
        <?= Html::a('← Volver al listado', ['index'], ['class' => 'btn rfv-btn-volver btn-sm px-3 py-2 align-self-start align-self-sm-auto']) ?>
    </div>

    <div class="row g-3 g-lg-4">
        <!-- Detalles del reporte -->
        <div class="col-12 col-lg-7">
            <div class="card rfv-card border-0 rounded-3 h-100 overflow-hidden">
                <div class="card-header rfv-card-header fw-bold py-3 px-4">Información del reporte</div>
                <div class="card-body p-4">
                    <p class="mb-2"><strong class="rfv-dato-label">Reportado por:</strong> <?= Html::encode($model->nombre) ?></p>
                    <p class="mb-2 text-break"><strong class="rfv-dato-label">Correo:</strong> <?= Html::mailto($model->email) ?></p>
                    <p class="mb-2"><strong class="rfv-dato-label">Tipo de falla:</strong> <?= Html::encode($model->categoria) ?></p>
                    <p class="mb-3"><strong class="rfv-dato-label">Fecha:</strong> <?= Yii::$app->formatter->asDatetime($model->created_at, 'php:d/m/Y H:i') ?></p>

                    <hr class="my-3" style="border-color: rgba(107, 15, 26, 0.12);">
                    <p class="fw-semibold mb-2 rfv-dato-label">Descripción del problema:</p>
                    <div class="p-3 rfv-descripcion-box rounded-3 border text-break">
                        <?= nl2br(Html::encode($model->descripcion)) ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Panel de seguimiento Admin / Superusuario -->
        <div class="col-12 col-lg-5">
            <div class="card rfv-card border-0 rounded-3 overflow-hidden">
                <div class="card-header rfv-card-header fw-bold py-3 px-4">Seguimiento técnico</div>
                <div class="card-body p-4">
                    <?php $form = ActiveForm::begin(); ?>

                    <?= $form->field($model, 'estado')->dropDownList(ReporteFalla::getEstados(), [
                        'class' => 'form-select',
                    ]) ?>

                    <?= $form->field($model, 'notas_admin')->textarea([
                        'rows' => 4,
                        'placeholder' => 'Escribe qué se revisó o cómo se solucionó la falla...',
                    ]) ?>

                    <div class="d-grid mt-3">
                        <?= Html::submitButton('Guardar cambios', [
                            'class' => 'btn rfv-btn-guardar text-white py-2 fw-semibold',
                            'style' => 'background-color: #7d0d30;',
                        ]) ?>
                    </div>

                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>