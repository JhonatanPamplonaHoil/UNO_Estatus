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

<div class="reporte-falla-index">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <h1 class="h3 fw-bold mb-0"><?= Html::encode($this->title) ?></h1>

        <div class="btn-group" role="group">
            <?= Html::a('Todos', ['index'], ['class' => 'btn btn-sm ' . ($estadoFiltro === null ? 'btn-dark' : 'btn-outline-dark')]) ?>
            <?= Html::a('Pendientes', ['index', 'estado' => ReporteFalla::ESTADO_PENDIENTE], ['class' => 'btn btn-sm ' . ($estadoFiltro === ReporteFalla::ESTADO_PENDIENTE ? 'btn-danger' : 'btn-outline-danger')]) ?>
            <?= Html::a('En revisión', ['index', 'estado' => ReporteFalla::ESTADO_EN_REVISION], ['class' => 'btn btn-sm ' . ($estadoFiltro === ReporteFalla::ESTADO_EN_REVISION ? 'btn-warning' : 'btn-outline-warning')]) ?>
            <?= Html::a('Resueltos', ['index', 'estado' => ReporteFalla::ESTADO_RESUELTO], ['class' => 'btn btn-sm ' . ($estadoFiltro === ReporteFalla::ESTADO_RESUELTO ? 'btn-success' : 'btn-outline-success')]) ?>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'tableOptions' => ['class' => 'table table-hover align-middle mb-0'],
                'columns' => [
                    [
                        'attribute' => 'id',
                        'headerOptions' => ['style' => 'width: 70px;'],
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
                            return Html::tag('span', Html::encode($model->estado), ['class' => "badge {$badge}"]);
                        },
                    ],
                    [
                        'attribute' => 'created_at',
                        'format' => ['datetime', 'php:d/m/Y H:i'],
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{view} {delete}',
                        'buttons' => [
                            'view' => static fn (string $url): string => Html::a('Revisar', $url, ['class' => 'btn btn-sm btn-outline-primary']),
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