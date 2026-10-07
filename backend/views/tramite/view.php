<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\ArrayHelper;
use common\models\EtapaTramite;

/** @var yii\web\View $this */
/** @var common\models\TramiteTitulacion $model */
/** @var common\models\AuditoriaTramite[] $historialAuditoria */

$this->title = 'Expediente: ' . $model->folio;
$this->params['breadcrumbs'][] = ['label' => 'Trámites de Titulación', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

$etapasDisponibles = ArrayHelper::map(EtapaTramite::find()->orderBy(['id' => SORT_ASC])->all(), 'id', 'nombre');
?>
<div class="tramite-titulacion-view">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1><?= Html::encode($this->title) ?></h1>
        <div>
            <?= Html::a('Actualizar Datos', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Eliminar', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => '¿Estás seguro de eliminar este trámite?',
                    'method' => 'post',
                ],
            ]) ?>
        </div>
    </div>

    <!-- PANEL DE CAMBIO DE ETAPA (SERVICIOS ESCOLARES) -->
    <div class="card border-primary mb-4">
        <div class="card-header bg-primary text-white fw-bold">
            Actualizar Etapa del Trámite (Dispara Auditoría Inmutable)
        </div>
        <div class="card-body">
            <?= Html::beginForm(['cambiar-etapa', 'id' => $model->id], 'post', ['class' => 'row g-3 align-items-end']) ?>
                <div class="col-md-4">
                    <label class="form-label fw-bold">Nueva Etapa:</label>
                    <?= Html::dropDownList('nueva_etapa_id', $model->etapa_actual_id, $etapasDisponibles, [
                        'class' => 'form-select',
                        'prompt' => '-- Selecciona una etapa --',
                        'required' => true,
                    ]) ?>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">Motivo o Justificación del Cambio:</label>
                    <?= Html::textInput('motivo', '', [
                        'class' => 'form-control',
                        'placeholder' => 'Ej. Documentación validada y sellada con éxito.',
                        'required' => true,
                    ]) ?>
                </div>
                <div class="col-md-2">
                    <?= Html::submitButton('Aplicar Cambio', ['class' => 'btn btn-success w-100']) ?>
                </div>
            <?= Html::endForm() ?>
        </div>
    </div>

    <!-- DETALLE DEL EXPEDIENTE -->
    <div class="card mb-4">
        <div class="card-header fw-bold">
            Información del Alumno y Solicitud
        </div>
        <div class="card-body p-0">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'id',
                    'folio',
                    'matricula',
                    [
                        'attribute' => 'user_id',
                        'label' => 'Alumno / Usuario Registrado',
                        'value' => function ($model) {
                            return $model->user ? $model->user->username . ' (' . $model->user->email . ')' : '-';
                        },
                    ],
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
    </div>

    <!-- TABLA DE HISTORIAL / AUDITORÍA -->
    <div class="card">
        <div class="card-header bg-dark text-white fw-bold">
            Bitácora de Auditoría (Historial Inmutable de Cambios)
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover mb-0 text-center align-middle">
                    <thead class="table-secondary">
                        <tr>
                            <th>#</th>
                            <th>Etapa Anterior</th>
                            <th>Etapa Nueva</th>
                            <th>Responsable</th>
                            <th>Motivo / Justificación</th>
                            <th>Dirección IP</th>
                            <th>Fecha y Hora</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($historialAuditoria)): ?>
                            <tr>
                                <td colspan="7" class="text-muted py-3">No hay registros de cambios de etapa aún.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($historialAuditoria as $index => $item): ?>
                                <tr>
                                    <td><?= $index + 1 ?></td>
                                    <td><span class="badge bg-secondary"><?= Html::encode($item->etapaAnterior->nombre ?? 'Inicial / Ninguna') ?></span></td>
                                    <td><span class="badge bg-success"><?= Html::encode($item->etapaNueva->nombre ?? '-') ?></span></td>
                                    <td><strong><?= Html::encode($item->usuarioResponsable->username ?? 'ID: ' . $item->usuario_responsable_id) ?></strong></td>
                                    <td class="text-start"><?= Html::encode($item->motivo_cambio) ?></td>
                                    <td><code><?= Html::encode($item->ip_origen) ?></code></td>
                                    <td><?= Yii::$app->formatter->asDatetime($item->created_at) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
