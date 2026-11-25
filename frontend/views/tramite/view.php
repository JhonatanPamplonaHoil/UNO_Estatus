<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\TramiteTitulacion $model */

$this->title = 'Detalle de Solicitud: ' . $model->folio;
$this->params['breadcrumbs'][] = ['label' => 'Mis Trámites', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<style>
    .tramite-titulacion-view .uno-detail-card {
        background-color: rgba(255, 255, 255, 0.96);
        border: 1px solid rgba(158, 42, 75, 0.18);
        border-radius: 14px;
        box-shadow: 0 10px 26px rgba(68, 12, 28, 0.1);
        overflow: hidden;
    }

    .tramite-titulacion-view .uno-detail-banner {
        background-color: #5c0f24;
        padding: 1.35rem 1.75rem;
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
        border-bottom: 2px solid rgba(233, 190, 88, 0.55);
    }

    .tramite-titulacion-view .uno-detail-banner-text {
        min-width: 0;
        flex: 1 1 auto;
    }

    .tramite-titulacion-view .uno-detail-title {
        margin: 0;
        font-size: clamp(1.35rem, 2.3vw, 1.9rem);
        font-weight: 700;
        color: #ffffff;
        letter-spacing: -0.01em;
        line-height: 1.25;
        word-break: break-word;
    }

    .tramite-titulacion-view .uno-detail-subtitle {
        margin: 0.3rem 0 0;
        font-size: 0.92rem;
        color: rgba(255, 255, 255, 0.78);
        line-height: 1.35;
    }

    .tramite-titulacion-view .uno-btn-cita {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        padding: 0.58rem 1.15rem;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.92rem;
        text-decoration: none;
        color: #2c1018;
        background: linear-gradient(135deg, #f5cf72 0%, #e9be58 100%);
        border: 1px solid rgba(255, 255, 255, 0.45);
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.22);
        transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
        white-space: nowrap;
        flex-shrink: 0;
    }

    .tramite-titulacion-view .uno-btn-cita:hover,
    .tramite-titulacion-view .uno-btn-cita:focus {
        transform: translateY(-2px);
        color: #240c12;
        background: linear-gradient(135deg, #fad986 0%, #eec25a 100%);
        box-shadow: 0 8px 20px rgba(233, 190, 88, 0.4);
    }

    .tramite-titulacion-view .uno-detail-body {
        padding: 1.25rem 1.5rem 1.5rem;
    }

    .tramite-titulacion-view .uno-detail-table-wrap {
        border-radius: 10px;
        overflow-x: auto;
        border: 1px solid rgba(158, 42, 75, 0.14);
        background-color: #ffffff;
    }

    .tramite-titulacion-view .detail-view {
        margin-bottom: 0;
        width: 100%;
    }

    .tramite-titulacion-view .detail-view th {
        width: 28%;
        min-width: 165px;
        background-color: rgba(158, 42, 75, 0.05);
        color: #4a1c2a;
        font-weight: 600;
        padding: 0.85rem 1rem;
        border-color: rgba(158, 42, 75, 0.12);
        vertical-align: middle;
    }

    .tramite-titulacion-view .detail-view td {
        color: #2d141c;
        padding: 0.85rem 1rem;
        border-color: rgba(158, 42, 75, 0.12);
        vertical-align: middle;
        word-break: break-word;
    }

    .tramite-titulacion-view .detail-view tr:hover th,
    .tramite-titulacion-view .detail-view tr:hover td {
        background-color: rgba(233, 190, 88, 0.08);
    }

    .tramite-titulacion-view .uno-etapa-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.42rem 0.85rem;
        border-radius: 999px;
        font-size: 0.86rem !important;
        font-weight: 600;
        color: #2c1018 !important;
        background: linear-gradient(135deg, #f5cf72 0%, #e9be58 100%) !important;
        border: 1px solid rgba(158, 42, 75, 0.25);
        box-shadow: 0 3px 10px rgba(233, 190, 88, 0.3);
    }

    @media (max-width: 767.98px) {
        .tramite-titulacion-view .uno-detail-banner {
            padding: 1.1rem 1.15rem;
        }

        .tramite-titulacion-view .uno-detail-body {
            padding: 1rem;
        }

        .tramite-titulacion-view .detail-view th,
        .tramite-titulacion-view .detail-view td {
            display: block;
            width: 100%;
            min-width: 0;
            padding: 0.65rem 0.85rem;
        }

        .tramite-titulacion-view .detail-view th {
            border-bottom: none;
            padding-bottom: 0.25rem;
        }

        .tramite-titulacion-view .detail-view td {
            border-top: none;
            padding-top: 0.25rem;
            padding-bottom: 0.75rem;
        }
    }

    @media (max-width: 575.98px) {
        .tramite-titulacion-view .uno-btn-cita {
            width: 100%;
            justify-content: center;
            white-space: normal;
            text-align: center;
        }
    }
</style>
<div class="tramite-titulacion-view">
    <div class="uno-detail-card">
        <div class="uno-detail-banner">
            <div class="uno-detail-banner-text">
                <h1 class="uno-detail-title"><?= Html::encode($this->title) ?></h1>
                <p class="uno-detail-subtitle">Consulta la información y el seguimiento de tu solicitud de titulación</p>
            </div>

            <?php if ($model->etapaActual && $model->etapaActual->permite_cita): ?>
                <?= Html::a('Agendar Cita para Recoger Título', ['cita/create', 'tramite_id' => $model->id], ['class' => 'btn btn-success uno-btn-cita']) ?>
            <?php endif; ?>
        </div>

        <div class="uno-detail-body">
            <div class="uno-detail-table-wrap">
                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        'folio',
                        'matricula',
                        [
                            'attribute' => 'carrera_id',
                            'value' => function ($model) {
                                return $model->carrera ? $model->carrera->nombre : '-';
                            },
                        ],
                        [
                            'attribute' => 'modalidad_id',
                            'value' => function ($model) {
                                return $model->modalidad ? $model->modalidad->nombre : '-';
                            },
                        ],
                        [
                            'attribute' => 'etapa_actual_id',
                            'format' => 'raw',
                            'value' => function ($model) {
                                return '<span class="badge bg-primary fs-6 uno-etapa-badge">' . Html::encode($model->etapaActual->nombre ?? '-') . '</span>';
                            },
                        ],
                        'promedio_general',
                        'observaciones:ntext',
                        'created_at:datetime',
                        'updated_at:datetime',
                    ],
                ]) ?>
            </div>
        </div>
    </div>
</div>