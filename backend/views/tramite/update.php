<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\TramiteTitulacion $model */

$this->title = 'Update Tramite Titulacion: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Tramite Titulacions', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<style>
    .tramite-titulacion-update .form-label {
        color: #4a0814;
        font-weight: 700;
        margin-bottom: 6px;
    }
    .tramite-titulacion-update .form-control,
    .tramite-titulacion-update .form-select,
    .tramite-titulacion-update textarea {
        border: 1.5px solid rgba(107, 15, 26, 0.28);
        border-radius: 9px;
        padding: 10px 14px;
        background-color: #ffffff;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }
    .tramite-titulacion-update .form-control:focus,
    .tramite-titulacion-update .form-select:focus,
    .tramite-titulacion-update textarea:focus {
        border-color: #c59b27;
        box-shadow: 0 0 0 0.22rem rgba(197, 155, 39, 0.22);
        outline: none;
    }
    .tramite-titulacion-update .form-group,
    .tramite-titulacion-update .mb-3 {
        margin-bottom: 1.15rem;
    }
    .tramite-titulacion-update button[type="submit"],
    .tramite-titulacion-update .btn-success,
    .tramite-titulacion-update .btn-primary {
        background: linear-gradient(135deg, #f3d27a 0%, #d4a638 100%);
        border: 1.5px solid #6b0f1a;
        color: #3a060f;
        font-weight: 700;
        border-radius: 8px;
        padding: 10px 24px;
        box-shadow: 0 4px 12px rgba(212, 166, 56, 0.25);
    }
</style>

<div class="tramite-titulacion-update">

    <div class="d-flex justify-content-between align-items-center pb-2 mb-4" style="border-bottom: 2px solid rgba(197, 155, 39, 0.55);">
        <h1 style="color: #4a0814; font-weight: 700; margin: 0;"><?= Html::encode($this->title) ?></h1>
    </div>

    <div class="card mb-4" style="border: 1.5px solid rgba(107, 15, 26, 0.35); border-top: 4px solid #6b0f1a; border-radius: 14px; overflow: hidden; box-shadow: 0 8px 22px rgba(74, 8, 20, 0.1);">
        <div class="card-header text-white fw-bold" style="background: linear-gradient(90deg, #5a0b15 0%, #7a1220 100%); border-bottom: 2px solid #c59b27; padding: 14px 20px;">
            Edición de Expediente y Datos del Trámite
        </div>
        <div class="card-body" style="background-color: #fffdfd; padding: 26px; border-top: 1px solid rgba(197, 155, 39, 0.25);">
            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>
        </div>
    </div>

</div>
