<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\CitaEntrega $model */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Cita Entregas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

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

    .cita-entrega-view {
        padding-top: 0.5rem;
        padding-bottom: 2rem;
    }

    .uno-view-header {
        background: linear-gradient(135deg, var(--uno-guinda) 0%, var(--uno-guinda-oscuro) 100%);
        color: #ffffff;
        padding: 1.4rem 1.75rem;
        border-radius: 14px;
        border-bottom: 4px solid var(--uno-dorado);
        box-shadow: 0 6px 18px rgba(107, 29, 58, 0.16);
        margin-bottom: 1.25rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .uno-view-header h1 {
        font-size: 1.55rem;
        font-weight: 700;
        margin: 0;
        color: #ffffff;
    }

    .uno-view-subtitle {
        margin: 0.25rem 0 0 0;
        font-size: 0.9rem;
        color: rgba(255, 255, 255, 0.85);
    }

    .uno-view-actions {
        display: flex;
        gap: 0.6rem;
        flex-wrap: wrap;
        margin-bottom: 1.25rem;
    }

    .btn-uno-dorado {
        background-color: var(--uno-dorado) !important;
        border-color: var(--uno-dorado) !important;
        color: #2C2427 !important;
        font-weight: 700;
        border-radius: 8px;
        padding: 0.5rem 1.15rem;
        transition: all 0.2s ease;
    }

    .btn-uno-dorado:hover {
        background-color: var(--uno-dorado-claro) !important;
        border-color: var(--uno-dorado-claro) !important;
        color: #2C2427 !important;
    }

    .btn-uno-eliminar {
        border-radius: 8px;
        font-weight: 600;
        padding: 0.5rem 1.15rem;
    }

    .uno-detail-card {
        background: #ffffff;
        border-radius: 14px;
        border: 1px solid var(--uno-borde);
        border-top: 4px solid var(--uno-guinda);
        box-shadow: 0 8px 24px rgba(44, 36, 39, 0.07);
        overflow: hidden;
    }

    .uno-detail-card .table.detail-view {
        margin-bottom: 0;
    }

    .uno-detail-card .table.detail-view th {
        width: 240px;
        background-color: #FAF4F6;
        color: var(--uno-guinda);
        font-weight: 700;
        border-color: var(--uno-borde);
        padding: 0.85rem 1.15rem;
    }

    .uno-detail-card .table.detail-view td {
        color: #2C2427;
        border-color: var(--uno-borde);
        padding: 0.85rem 1.15rem;
        vertical-align: middle;
    }

    .uno-detail-card .table.detail-view tr:hover td {
        background-color: var(--uno-guinda-suave);
    }
");
?>
<div class="cita-entrega-view">

    <div class="uno-view-header">
        <div>
            <h1>Detalle de Cita #<?= Html::encode($this->title) ?></h1>
            <p class="uno-view-subtitle">Información registrada de la cita de entrega</p>
        </div>
    </div>

    <p class="uno-view-actions">
        <?= Html::a('Actualizar', ['update', 'id' => $model->id], ['class' => 'btn btn-primary btn-uno-dorado']) ?>
        <?= Html::a('Eliminar', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger btn-uno-eliminar',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <div class="uno-detail-card">
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'id',
                'tramite_id',
                'fecha',
                'hora',
                'estado',
                'comentarios',
                'created_at',
                'updated_at',
            ],
        ]) ?>
    </div>

</div>