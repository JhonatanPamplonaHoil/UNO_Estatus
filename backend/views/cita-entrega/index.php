<?php

use common\models\CitaEntrega;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var backend\models\search\CitaEntregaSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Citas Agendadas';
$this->params['breadcrumbs'][] = $this->title;

$this->registerJs("
    const inputBusquedaCita = document.getElementById('unoQuickSearchCitaEntrega');
    if (inputBusquedaCita) {
        inputBusquedaCita.addEventListener('keyup', function () {
            const filtro = this.value.toLowerCase().trim();
            const filas = document.querySelectorAll('#uno-citas-agendadas-grid table tbody tr');
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
    .uno-grid table tbody td a.btn-outline-secondary {
        color: #7a122c;
        border-color: #7a122c;
    }
    .uno-grid table tbody td a.btn-outline-secondary:hover {
        background-color: #7a122c;
        color: #ffffff;
        text-decoration: none;
    }
    .uno-grid table tbody td a.btn-outline-danger {
        color: #dc3545;
    }
    .uno-grid table tbody td a.btn-outline-danger:hover {
        color: #ffffff;
        text-decoration: none;
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

<div class="cita-entrega-index py-2">

    <!-- Cabecera visual institucional -->
    <div class="uno-page-header p-4 mb-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
        <div>
            <h1 class="h2 fw-bold mb-1"><?= Html::encode($this->title) ?></h1>
            <p class="mb-0 small" style="opacity: 0.9;">
                Control y seguimiento de citas programadas para entrega de documentación en la UNO.
            </p>
        </div>
        <div>
            <span class="badge px-3 py-2 rounded-pill fw-bold" style="background-color: #ffc107; color: #1f1f1f;">
                Control de Citas UNO
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
                    <input type="text" id="unoQuickSearchCitaEntrega" class="form-control uno-quick-search" placeholder="Búsqueda rápida por alumno, matrícula, carrera, fecha o estado...">
                </div>
            </div>
        </div>

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'options' => ['id' => 'uno-citas-agendadas-grid', 'class' => 'grid-view uno-grid table-responsive'],
            'tableOptions' => ['class' => 'table table-hover align-middle mb-0'],
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                [
                    'attribute' => 'alumnoInfo', // Conectamos con el Search Model
                    'label' => 'Alumno (Matrícula) - Carrera',
                    'value' => function ($model) {
                        $tramite = $model->tramite;
                        if ($tramite) {
                            $nombreAlumno = $tramite->user ? $tramite->user->username : 'Sin Asignar';
                            $matricula = $tramite->matricula ?? 'N/A';
                            $carrera = $tramite->carrera ? $tramite->carrera->nombre : 'N/A'; 
                            
                            return "{$nombreAlumno} ({$matricula}) - {$carrera}";
                        }
                        return 'Trámite no encontrado';
                    },
                ],
                [
                    'attribute' => 'fecha',
                    'format' => ['date', 'php:d/m/Y'],
                    'filterInputOptions' => [
                        'class' => 'form-control',
                        'placeholder' => 'Ej: 2026-11-03'
                    ],
                ],
                'hora',
                'estado',
                [
                    'class' => ActionColumn::className(),
                    'template' => '{view} {delete}',
                    'contentOptions' => ['class' => 'text-nowrap'],
                    'buttons' => [
                        'view' => function ($url, CitaEntrega $model, $key) {
                            return Html::a(
                                '<svg aria-hidden="true" style="display:inline-block;font-size:inherit;height:1em;overflow:visible;vertical-align:-.125em;width:1.125em" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path fill="currentColor" d="M572.52 241.4C518.29 135.59 410.93 64 288 64S57.68 135.64 3.48 241.41a32.35 32.35 0 000 29.19C57.71 376.41 165.07 448 288 448s230.32-71.64 284.52-177.41a32.35 32.35 0 000-29.19zM288 400a144 144 0 11144-144 143.93 143.93 0 01-144 144zm0-240a95.31 95.31 0 00-25.31 3.79 47.85 47.85 0 01-66.9 66.9A95.78 95.78 0 10288 160z"/></svg> Ver cita',
                                $url,
                                [
                                    'class' => 'btn btn-sm btn-outline-secondary',
                                    'title' => 'Ver cita',
                                    'aria-label' => 'Ver cita',
                                    'data-pjax' => '0',
                                ]
                            );
                        },
                        'delete' => function ($url, CitaEntrega $model, $key) {
                            return Html::a(
                                '<svg aria-hidden="true" style="display:inline-block;font-size:inherit;height:1em;overflow:visible;vertical-align:-.125em;width:.875em" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path fill="currentColor" d="M32 464a48 48 0 0048 48h288a48 48 0 0048-48V128H32zm272-256a16 16 0 0132 0v224a16 16 0 01-32 0zm-96 0a16 16 0 0132 0v224a16 16 0 01-32 0zm-96 0a16 16 0 0132 0v224a16 16 0 01-32 0zM432 32H312l-9-19a24 24 0 00-22-13H167a24 24 0 00-22 13l-9 19H16A16 16 0 000 48v32a16 16 0 0016 16h416a16 16 0 0016-16V48a16 16 0 00-16-16z"/></svg> Cancelar',
                                $url,
                                [
                                    'class' => 'btn btn-sm btn-outline-danger ms-2',
                                    'title' => 'Cancelar cita',
                                    'aria-label' => 'Cancelar cita',
                                    'data-confirm' => '¿Deseas cancelar esta cita? Se le enviará un correo de notificación al alumno.',
                                    'data-method' => 'post',
                                    'data-pjax' => '0',
                                ]
                            );
                        },
                    ],
                    'urlCreator' => function ($action, CitaEntrega $model, $key, $index, $column) {
                        return Url::toRoute([$action, 'id' => $model->id]);
                    },
                ],
            ],
        ]); ?>

    </div>

</div>