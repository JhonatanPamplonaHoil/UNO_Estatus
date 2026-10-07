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
<div class="tramite-titulacion-view">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1><?= Html::encode($this->title) ?></h1>
        
        <?php if ($model->etapaActual && $model->etapaActual->permite_cita): ?>
            <?= Html::a('Agendar Cita para Recoger Título', ['cita/create', 'tramite_id' => $model->id], ['class' => 'btn btn-success']) ?>
        <?php endif; ?>
    </div>

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
                    return '<span class="badge bg-primary fs-6">' . Html::encode($model->etapaActual->nombre ?? '-') . '</span>';
                },
            ],
            'promedio_general',
            'observaciones:ntext',
            'created_at:datetime',
            'updated_at:datetime',
        ],
    ]) ?>

</div>
