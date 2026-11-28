<?php

use common\models\TramiteTitulacion;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var frontend\models\search\TramiteTitulacionSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Estatus de mi título';
$this->params['breadcrumbs'][] = $this->title;

// Verificar si el alumno ya cuenta con un trámite registrado
$yaTieneTramite = TramiteTitulacion::find()
    ->where(['user_id' => Yii::$app->user->id])
    ->exists();
?>

<style>
/* Estilos de Interfaz del Sistema UNO */
.tramite-card {
    border: none;
    border-radius: 1.25rem;
    box-shadow: 0 15px 35px rgba(92, 6, 32, 0.12), 0 5px 15px rgba(0, 0, 0, 0.04);
    overflow: hidden;
    background-color: #ffffff;
}

.tramite-header {
    background: linear-gradient(135deg, #4a041a 0%, #7d0d30 100%);
    color: #ffffff;
    padding: 1.75rem 2rem;
    border-bottom: 3px solid #5c0620;
}

.btn-uno-success {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: #ffffff !important;
    border: none;
    font-weight: 600;
    padding: 0.65rem 1.3rem;
    border-radius: 0.6rem;
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    transition: all 0.25s ease;
    text-decoration: none;
}

.btn-uno-success:hover {
    background: linear-gradient(135deg, #059669 0%, #047857 100%);
    transform: translateY(-2px);
    box-shadow: 0 6px 18px rgba(16, 185, 129, 0.4);
}

.custom-table {
    margin-bottom: 0;
}

.custom-table thead th {
    background-color: #f8fafc !important;
    color: #475569;
    font-weight: 700;
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border-bottom: 2px solid #e2e8f0;
    padding: 1rem 0.75rem;
}

.custom-table tbody tr {
    transition: background-color 0.2s ease;
}

.custom-table tbody tr:hover {
    background-color: #fcf8f9 !important;
}

.custom-table .form-control {
    border-color: #cbd5e1;
    border-radius: 0.5rem;
    font-size: 0.875rem;
    padding: 0.45rem 0.65rem;
    background-color: #f8fafc;
}

.custom-table .form-control:focus {
    border-color: #7d0d30;
    box-shadow: 0 0 0 3px rgba(125, 13, 48, 0.15);
    background-color: #ffffff;
}

.custom-table a.folio-link {
    color: #7d0d30;
    font-weight: 700;
}

.custom-table a.folio-link:hover {
    color: #9e133c;
    text-decoration: underline !important;
}

.custom-table .badge.bg-primary {
    background-color: #7d0d30 !important;
    font-size: 0.8rem !important;
    padding: 0.45em 0.75em;
    font-weight: 600;
    border-radius: 0.4rem;
}

.empty-state-box {
    padding: 3.5rem 1.5rem;
    text-align: center;
    color: #64748b;
}
</style>

<div class="tramite-titulacion-index py-3">

    <div class="card tramite-card">
        <!-- Banner del título e institucional -->
        <div class="tramite-header d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <h2 class="h3 fw-bold mb-1 text-white"><?= Html::encode($this->title) ?></h2>
                <p class="mb-0 opacity-75 small">Consulta y monitorea el estado de tus solicitudes de titulación</p>
            </div>
            <div>
                <?php if (!$yaTieneTramite): ?>
                    <?= Html::a('➕ Nueva Solicitud de Trámite', ['create'], ['class' => 'btn btn-uno-success d-inline-flex align-items-center']) ?>
                <?php else: ?>
                    <span class="badge bg-light text-dark px-3 py-2 rounded-pill">
                         Trámite ya registrado
                    </span>
                <?php endif; ?>
            </div>
        </div>

        <!-- Tabla con GridView -->
        <div class="card-body p-0">
            <div class="table-responsive">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'tableOptions' => ['class' => 'table table-striped table-bordered text-center align-middle custom-table'],
                    'summary' => '<div class="px-3 py-2 text-muted small border-bottom bg-light text-start">Mostrando <b>{begin}-{end}</b> de <b>{totalCount}</b> registros.</div>',
                    'emptyText' => '<div class="empty-state-box"><div class="fs-1 mb-2">📋</div><h5 class="fw-bold text-dark mb-1">Sin trámites registrados</h5><p class="mb-0 text-muted small">Aún no cuentas con solicitudes de titulación registradas en el sistema.</p></div>',
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],

                        [
                            'attribute' => 'folio',
                            'format' => 'raw',
                            'value' => function ($model) {
                                return Html::a(Html::encode($model->folio), ['view', 'id' => $model->id], ['class' => 'fw-bold text-decoration-none folio-link']);
                            },
                        ],
                        'matricula',
                        [
                            'attribute' => 'carrera_id',
                            'label' => 'Carrera',
                            'value' => function ($model) {
                                return $model->carrera ? $model->carrera->nombre : '-';
                            },
                        ],
                        [
                            'attribute' => 'modalidad_id',
                            'label' => 'Modalidad',
                            'value' => function ($model) {
                                return $model->modalidad ? $model->modalidad->nombre : '-';
                            },
                        ],
                        [
                            'attribute' => 'etapa_actual_id',
                            'label' => 'Estado / Etapa',
                            'format' => 'raw',
                            'value' => function ($model) {
                                return '<span class="badge bg-primary fs-6">' . Html::encode($model->etapaActual->nombre ?? '-') . '</span>';
                            },
                        ],
                        'created_at:date',

                        [
                            'class' => ActionColumn::class,
                            'template' => '{view}', // El alumno solo tiene permitido ver el detalle
                            'urlCreator' => function ($action, TramiteTitulacion $model, $key, $index, $column) {
                                return Url::toRoute([$action, 'id' => $model->id]);
                            }
                        ],
                    ],
                ]); ?>
            </div>
        </div>
    </div>

</div>