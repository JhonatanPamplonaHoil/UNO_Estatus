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
?>
<div class="tramite-titulacion-index">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'tableOptions' => ['class' => 'table table-striped table-bordered text-center align-middle'],
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