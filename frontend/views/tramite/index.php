<?php

use common\models\TramiteTitulacion;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var frontend\models\search\TramiteTitulacionSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Mis Trámites de Titulación';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tramite-titulacion-index">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><?= Html::encode($this->title) ?></h1>
        <p class="mb-0">
            <?= Html::a('Nueva Solicitud de Trámite', ['create'], ['class' => 'btn btn-success']) ?>
        </p>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'tableOptions' => ['class' => 'table table-striped table-bordered text-center align-middle'],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'folio',
                'format' => 'raw',
                'value' => function ($model) {
                    return Html::a(Html::encode($model->folio), ['view', 'id' => $model->id], ['class' => 'fw-bold text-decoration-none']);
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
                'class' => ActionColumn::className(),
                'template' => '{view}', // El alumno solo tiene permitido ver el detalle
                'urlCreator' => function ($action, TramiteTitulacion $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                }
            ],
        ],
    ]); ?>

</div>