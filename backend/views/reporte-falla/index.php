<?php

declare(strict_types=1);

use common\models\ReporteFalla;
use yii\bootstrap5\Html;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var string|null $estadoFiltro */

$this->title = 'Reportes de Fallas';
$this->params['breadcrumbs'][] = $this->title;
?>

<style>
    .reporte-falla-index .rf-banner {
        background: linear-gradient(90deg, #6b0f1a 0%, #7d121f 100%);
        color: #ffffff;
        border-radius: 14px;
        padding: 22px 26px;
        box-shadow: 0 8px 20px rgba(107, 15, 26, 0.16);
    }

    .reporte-falla-index .rf-banner-sub {
        color: rgba(255, 255, 255, 0.88);
        font-size: 0.92rem;
    }

    .reporte-falla-index .rf-banner-filtros {
        background-color: #480810;
        padding: 6px;
        border-radius: 11px;
        border: 1px solid rgba(255, 255, 255, 0.22);
    }

    .reporte-falla-index .rf-banner-filtros .btn {
        border-radius: 8px !important;
        font-weight: 600;
        transition: all 0.15s ease-in-out;
    }

    /* Todos: Dorado */
    .reporte-falla-index .rf-btn-todos-on {
        background-color: #f4b400;
        border: 1px solid #f4b400;
        color: #1f1600;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.25);
    }

    .reporte-falla-index .rf-btn-todos-off {
        background-color: #fff8e1;
        border: 1px solid #f4b400;
        color: #5c4200;
    }

    .reporte-falla-index .rf-btn-todos-off:hover {
        background-color: #f4b400;
        color: #1f1600;
    }

    /* Pendientes: Rojo */
    .reporte-falla-index .rf-btn-pendiente-on {
        background-color: #dc3545;
        border: 1px solid #ff6b7a;
        color: #ffffff;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.25);
    }

    .reporte-falla-index .rf-btn-pendiente-off {
        background-color: #ffe5e8;
        border: 1px solid #f5a3ab;
        color: #84141f;
    }

    .reporte-falla-index .rf-btn-pendiente-off:hover {
        background-color: #dc3545;
        border-color: #dc3545;
        color: #ffffff;
    }

    /* En revisión: Ámbar */
    .reporte-falla-index .rf-btn-revision-on {
        background-color: #ffc107;
        border: 1px solid #ffe07d;
        color: #1f1600;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.25);
    }

    .reporte-falla-index .rf-btn-revision-off {
        background-color: #fff4d6;
        border: 1px solid #f3cf76;
        color: #664800;
    }

    .reporte-falla-index .rf-btn-revision-off:hover {
        background-color: #ffc107;
        border-color: #ffc107;
        color: #1f1600;
    }

    /* Resueltos: Verde */
    .reporte-falla-index .rf-btn-resuelto-on {
        background-color: #198754;
        border: 1px solid #4cc38a;
        color: #ffffff;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.25);
    }

    .reporte-falla-index .rf-btn-resuelto-off {
        background-color: #e3f6ec;
        border: 1px solid #8fd3b1;
        color: #0f5132;
    }

    .reporte-falla-index .rf-btn-resuelto-off:hover {
        background-color: #198754;
        border-color: #198754;
        color: #ffffff;
    }

    .reporte-falla-index .rf-card {
        background-color: #ffffff;
        border: 1px solid rgba(107, 15, 26, 0.12);
        border-top: 4px solid #6b0f1a;
        border-radius: 14px;
        box-shadow: 0 10px 25px rgba(107, 15, 26, 0.08);
    }

    .reporte-falla-index .rf-thead th {
        background-color: #6b0f1a !important;
        color: #ffffff !important;
        font-weight: 600;
        font-size: 0.9rem;
        padding-top: 13px;
        padding-bottom: 13px;
        border: none !important;
        vertical-align: middle;
    }

    .reporte-falla-index .rf-thead th a {
        color: #ffffff !important;
        text-decoration: none;
        font-weight: 600;
    }

    .reporte-falla-index .rf-thead th a:hover {
        color: #ffe082 !important;
    }

    .reporte-falla-index .table tbody td {
        padding-top: 14px;
        padding-bottom: 14px;
        border-color: rgba(107, 15, 26, 0.08);
    }

    .reporte-falla-index .table-hover tbody tr:hover {
        background-color: #fdf7f7;
    }

    .reporte-falla-index .rf-badge {
        font-size: 0.78rem;
        font-weight: 600;
        padding: 5px 10px;
    }
</style>

<div class="reporte-falla-index">
    <div class="rf-banner d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h1 class="h3 fw-bold mb-1"><?= Html::encode($this->title) ?></h1>
            <div class="rf-banner-sub">Control y seguimiento de reportes de fallas registrados en el sistema.</div>
        </div>

        <div class="d-flex flex-wrap gap-2 rf-banner-filtros" role="group" aria-label="Filtrar por estado">
            <?= Html::a('Todos', ['index'], ['class' => 'btn btn-sm px-3 ' . ($estadoFiltro === null ? 'rf-btn-todos-on' : 'rf-btn-todos-off')]) ?>
            <?= Html::a('Pendientes', ['index', 'estado' => ReporteFalla::ESTADO_PENDIENTE], ['class' => 'btn btn-sm px-3 ' . ($estadoFiltro === ReporteFalla::ESTADO_PENDIENTE ? 'rf-btn-pendiente-on' : 'rf-btn-pendiente-off')]) ?>
            <?= Html::a('En revisión', ['index', 'estado' => ReporteFalla::ESTADO_EN_REVISION], ['class' => 'btn btn-sm px-3 ' . ($estadoFiltro === ReporteFalla::ESTADO_EN_REVISION ? 'rf-btn-revision-on' : 'rf-btn-revision-off')]) ?>
            <?= Html::a('Resueltos', ['index', 'estado' => ReporteFalla::ESTADO_RESUELTO], ['class' => 'btn btn-sm px-3 ' . ($estadoFiltro === ReporteFalla::ESTADO_RESUELTO ? 'rf-btn-resuelto-on' : 'rf-btn-resuelto-off')]) ?>
        </div>
    </div>

    <div class="card rf-card border-0 overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'layout' => "<div class=\"px-4 pt-3 pb-2 text-muted small\">{summary}</div>\n{items}\n<div class=\"px-4 py-3\">{pager}</div>",
                    'tableOptions' => ['class' => 'table table-hover align-middle mb-0'],
                    'headerRowOptions' => ['class' => 'rf-thead'],
                    'columns' => [
                        [
                            'attribute' => 'id',
                            'headerOptions' => ['style' => 'width: 75px;'],
                            'value' => static fn (ReporteFalla $model): string => '#' . $model->id,
                        ],
                        'nombre',
                        'email:email',
                        'categoria',
                        [
                            'attribute' => 'estado',
                            'format' => 'raw',
                            'value' => static function (ReporteFalla $model): string {
                                $badge = match ($model->estado) {
                                    ReporteFalla::ESTADO_PENDIENTE => 'bg-danger',
                                    ReporteFalla::ESTADO_EN_REVISION => 'bg-warning text-dark',
                                    ReporteFalla::ESTADO_RESUELTO => 'bg-success',
                                    default => 'bg-secondary',
                                };
                                return Html::tag('span', Html::encode($model->estado), ['class' => "badge rounded-pill rf-badge {$badge}"]);
                            },
                        ],
                        [
                            'attribute' => 'created_at',
                            'format' => ['datetime', 'php:d/m/Y H:i'],
                        ],
                        [
                            'class' => 'yii\grid\ActionColumn',
                            'headerOptions' => ['class' => 'text-end', 'style' => 'width: 175px;'],
                            'contentOptions' => ['class' => 'text-end text-nowrap'],
                            'template' => '{view} {delete}',
                            'buttons' => [
                                'view' => static fn (string $url): string => Html::a('Revisar', $url, ['class' => 'btn btn-sm btn-outline-primary me-1']),
                                'delete' => static fn (string $url): string => Html::a('Eliminar', $url, [
                                    'class' => 'btn btn-sm btn-outline-danger',
                                    'data' => [
                                        'confirm' => '¿Seguro que deseas eliminar este reporte?',
                                        'method' => 'post',
                                    ],
                                ]),
                            ],
                        ],
                    ],
                ]) ?>
            </div>
        </div>
    </div>
</div>