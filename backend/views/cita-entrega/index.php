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
?>
<div class="cita-entrega-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
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
                'urlCreator' => function ($action, CitaEntrega $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>

</div>