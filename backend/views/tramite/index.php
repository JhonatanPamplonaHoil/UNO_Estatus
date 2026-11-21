<?php

use common\models\TramiteTitulacion;
use common\models\Carrera;
use common\models\ModalidadTitulacion;
use common\models\EtapaTramite;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var backend\models\search\TramiteTitulacionSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Control Escolar - Trámites de Titulación';
$this->params['breadcrumbs'][] = $this->title;

$this->registerJs("
    const inputBusquedaTramite = document.getElementById('unoQuickSearchTramite');
    if (inputBusquedaTramite) {
        inputBusquedaTramite.addEventListener('keyup', function () {
            const filtro = this.value.toLowerCase().trim();
            const filas = document.querySelectorAll('#uno-tramites-grid table tbody tr');
            filas.forEach(function (fila) {
                if (fila.querySelector('.empty')) return;
                const textoFila = fila.textContent.toLowerCase();
                fila.style.display = textoFila.includes(filtro) ? '' : 'none';
            });
        });
    }
");
?>

<style>
    /* Encabezado institucional UNO */
    .uno-page-header {
        background-image: 
            linear-gradient(135deg, rgba(110, 14, 38, 0.92) 0%, rgba(145, 20, 51, 0.88) 100%),
            url('<?= Yii::getAlias('@web') ?>/images/fondo.png');
        background-size: cover;
        background-position: center;
        color: #ffffff;
        border-radius: 16px;
        box-shadow: 0 8px 22px rgba(110, 14, 38, 0.20);
    }

    /* Contenedor tipo tarjeta para la tabla */
    .uno-table-card {
        background: #ffffff;
        border: 1px solid #eaeaea;
        border-top: 4px solid #7a122c;
        border-radius: 16px;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.05);
        padding: 1.5rem;
    }

    /* Buscador rápido */
    .uno-quick-search {
        border: 1.5px solid #e0cfd3;
        border-radius: 10px;
        padding-left: 2.5rem;
        transition: all 0.2s ease;
    }
    .uno-quick-search:focus {
        border-color: #7a122c;
        box-shadow: 0 0 0 0.2rem rgba(122, 18, 44, 0.15);
    }
    .uno-search-wrapper {
        position: relative;
    }
    .uno-search-icon {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #7a122c;
        pointer-events: none;
    }

    /* Estilos de la tabla GridView */
    .uno-grid table thead th {
        background-color: #6e0e26;
        color: #ffffff;
        font-weight: 600;
        border-bottom: none;
        white-space: nowrap;
        padding: 0.85rem 0.75rem;
    }
    .uno-grid table thead th a {
        color: #ffffff;
        text-decoration: none;
    }
    .uno-grid table thead th a:hover {
        color: #ffc107;
    }
    .uno-grid table thead tr.filters td {
        background-color: #f9f3f5;
        padding: 0.5rem;
    }
    .uno-grid table thead tr.filters input,
    .uno-grid table thead tr.filters select {
        border-radius: 8px;
        border: 1px solid #d8c2c7;
        font-size: 0.875rem;
    }
    .uno-grid table tbody tr {
        transition: background-color 0.15s ease;
    }
    .uno-grid table tbody tr:hover {
        background-color: rgba(122, 18, 44, 0.04);
    }
    .uno-grid table tbody td a {
        color: #7a122c;
        font-weight: 600;
        text-decoration: none;
    }
    .uno-grid table tbody td a:hover {
        color: #b01a40;
        text-decoration: underline;
    }
    .uno-grid .badge {
        border-radius: 50rem;
        padding: 0.45em 0.9em;
        font-weight: 600;
    }

    /* Paginación institucional */
    .uno-grid .pagination .page-item.active .page-link {
        background-color: #7a122c;
        border-color: #7a122c;
        color: #ffffff;
    }
    .uno-grid .pagination .page-link {
        color: #7a122c;
    }
</style>

<div class="tramite-titulacion-index py-2">

    <!-- Cabecera visual institucional -->
    <div class="uno-page-header p-4 mb-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
        <div>
            <h1 class="h2 fw-bold mb-1"><?= Html::encode($this->title) ?></h1>
            <p class="mb-0 small" style="opacity: 0.9;">
                Seguimiento y gestión de expedientes y etapas de titulación en Control Escolar UNO.
            </p>
        </div>
        <div>
            <span class="badge px-3 py-2 rounded-pill fw-bold" style="background-color: #ffc107; color: #1f1f1f;">
                Control Escolar UNO
            </span>
        </div>
    </div>

    <!-- Tarjeta principal con Buscador Rápido + GridView -->
    <div class="uno-table-card">

        <!-- Barra de búsqueda rápida -->
        <div class="row align-items-center justify-content-between g-3 mb-4">
            <div class="col-md-6 col-lg-5">
                <div class="uno-search-wrapper">
                    <svg class="uno-search-icon" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m1.85-5.15a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" id="unoQuickSearchTramite" class="form-control uno-quick-search" placeholder="Búsqueda rápida por folio, matrícula, egresado, carrera o etapa...">
                </div>
            </div>
            <div class="col-md-auto text-muted small">
                Filtra al instante mientras escribes o usa los filtros de cada columna.
            </div>
        </div>

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'options' => ['id' => 'uno-tramites-grid', 'class' => 'grid-view uno-grid table-responsive'],
            'tableOptions' => ['class' => 'table table-hover text-center align-middle mb-0'],
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                [
                    'attribute' => 'folio',
                    'label' => 'Folio',
                    'format' => 'raw',
                    'value' => function ($model) {
                        return Html::a(Html::encode($model->folio), ['view', 'id' => $model->id], [
                            'class' => 'fw-bold text-decoration-none'
                        ]);
                    },
                ],
                'matricula',
                [
                    'attribute' => 'user_id',
                    'label' => 'Egresado',
                    'value' => function ($model) {
                        return $model->user ? $model->user->username : '-';
                    },
                ],
                [
                    'attribute' => 'carrera_id',
                    'label' => 'Carrera',
                    'value' => function ($model) {
                        return $model->carrera ? $model->carrera->nombre : '-';
                    },
                    'filter' => ArrayHelper::map(Carrera::find()->all(), 'id', 'nombre'),
                ],
                [
                    'attribute' => 'modalidad_id',
                    'label' => 'Modalidad',
                    'value' => function ($model) {
                        return $model->modalidad ? $model->modalidad->nombre : '-';
                    },
                    'filter' => ArrayHelper::map(ModalidadTitulacion::find()->all(), 'id', 'nombre'),
                ],
                [
                    'attribute' => 'etapa_actual_id',
                    'label' => 'Etapa Actual',
                    'format' => 'raw',
                    'value' => function ($model) {
                        return '<span class="badge bg-primary fs-6">' . Html::encode($model->etapaActual->nombre ?? '-') . '</span>';
                    },
                    'filter' => ArrayHelper::map(EtapaTramite::find()->orderBy(['id' => SORT_ASC])->all(), 'id', 'nombre'),
                ],
                'created_at:date',

                [
                    'class' => ActionColumn::className(),
                    'template' => '{view} {update}',
                    'urlCreator' => function ($action, TramiteTitulacion $model, $key, $index, $column) {
                        return Url::toRoute([$action, 'id' => $model->id]);
                    }
                ],
            ],
        ]); ?>

    </div>

</div>