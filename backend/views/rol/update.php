<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\Rol $model */

$this->title = 'Update Rol: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Rols', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';

$this->registerCss("
    :root {
        --uno-guinda: #6B1D3A;
        --uno-guinda-oscuro: #4E1329;
        --uno-guinda-suave: #FDF6F8;
        --uno-dorado: #C59B27;
        --uno-dorado-claro: #DFB94C;
        --uno-dorado-suave: #FBF8EE;
        --uno-borde: #E6DCE0;
    }

    .rol-update {
        padding-top: 0.5rem;
        padding-bottom: 2rem;
    }

    .uno-update-header {
        background: linear-gradient(135deg, var(--uno-guinda) 0%, var(--uno-guinda-oscuro) 100%);
        color: #ffffff;
        padding: 1.4rem 1.75rem;
        border-radius: 14px;
        border-bottom: 4px solid var(--uno-dorado);
        box-shadow: 0 6px 18px rgba(107, 29, 58, 0.16);
        margin-bottom: 1.25rem;
    }

    .uno-update-header h1 {
        font-size: 1.55rem;
        font-weight: 700;
        margin: 0;
        color: #ffffff;
    }

    .uno-update-subtitle {
        margin: 0.25rem 0 0 0;
        font-size: 0.9rem;
        color: rgba(255, 255, 255, 0.85);
    }

    .uno-form-card {
        background: #ffffff;
        padding: 1.65rem;
        border-radius: 14px;
        border: 1px solid var(--uno-borde);
        border-top: 4px solid var(--uno-guinda);
        box-shadow: 0 8px 24px rgba(44, 36, 39, 0.07);
    }

    .uno-form-card .form-group,
    .uno-form-card .mb-3 {
        margin-bottom: 1rem;
    }

    .uno-form-card label {
        font-weight: 600;
        color: var(--uno-guinda);
        margin-bottom: 0.35rem;
    }

    .uno-form-card .form-control {
        border-radius: 8px;
        border: 1px solid #D5C8CD;
        padding: 0.55rem 0.85rem;
    }

    .uno-form-card .form-control:focus {
        border-color: var(--uno-dorado);
        box-shadow: 0 0 0 0.2rem rgba(197, 155, 39, 0.25);
    }

    .uno-form-card .btn-primary,
    .uno-form-card .btn-success {
        background-color: var(--uno-guinda) !important;
        border-color: var(--uno-guinda) !important;
        color: #ffffff !important;
        font-weight: 700;
        border-radius: 8px;
        padding: 0.5rem 1.35rem;
        transition: all 0.2s ease;
    }

    .uno-form-card .btn-primary:hover,
    .uno-form-card .btn-success:hover {
        background-color: var(--uno-guinda-oscuro) !important;
        border-color: var(--uno-dorado) !important;
        color: #ffffff !important;
    }
");
?>
<div class="rol-update">

    <div class="uno-update-header">
        <h1><?= Html::encode($this->title) ?></h1>
        <p class="uno-update-subtitle">Modifica los datos del rol y su valor asignado</p>
    </div>

    <div class="uno-form-card">
        <?= $this->render('_form', [
            'model' => $model,
        ]) ?>
    </div>

</div>
