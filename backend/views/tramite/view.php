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
        <h1 style="color: #4a0814; font-weight: 700; margin: 0;"><?= Html::encode($this->title) ?></h1>
        <div class="d-flex gap-2">
            <?= Html::a('Actualizar Datos', ['update', 'id' => $model->id], [
                'class' => 'btn',
                'style' => 'background: linear-gradient(135deg, #f3d27a 0%, #d4a638 100%); border: 1.5px solid #6b0f1a; color: #3a060f; font-weight: 700; border-radius: 8px; padding: 8px 16px; box-shadow: 0 4px 12px rgba(212, 166, 56, 0.25);',
            ]) ?>
            <?= Html::a('Eliminar', ['delete', 'id' => $model->id], [
                'class' => 'btn',
                'style' => 'background-color: #fdf2f4; border: 1.5px solid #8a1625; color: #730d15; font-weight: 700; border-radius: 8px; padding: 8px 16px; box-shadow: 0 4px 10px rgba(115, 13, 21, 0.12);',
                'data' => [
                    'confirm' => '¿Estás seguro de eliminar este trámite?',
                    'method' => 'post',
                ],
            ]) ?>
        </div>
    </div>

    <!-- PANEL DE CAMBIO DE ETAPA (SERVICIOS ESCOLARES) -->
    <div class="card mb-4" style="border: 1px solid rgba(107, 15, 26, 0.2); border-radius: 12px; overflow: hidden;">
        <div class="card-header text-white fw-bold" style="background: linear-gradient(90deg, #5a0b15 0%, #7a1220 100%); border-bottom: 2px solid #c59b27; padding: 12px 18px;">
            Actualizar Etapa del Trámite (Dispara Auditoría Inmutable)
        </div>
        <div class="card-body" style="background-color: #fffdfd; padding: 20px;">
            <?= Html::beginForm(['cambiar-etapa', 'id' => $model->id], 'post', ['class' => 'row g-3 align-items-end']) ?>
                <div class="col-md-4">
                    <label class="form-label fw-bold" style="color: #4a0814;">Nueva Etapa:</label>
                    <?= Html::dropDownList('nueva_etapa_id', $model->etapa_actual_id, $etapasDisponibles, [
                        'class' => 'form-select',
                        'prompt' => '-- Selecciona una etapa --',
                        'required' => true,
                    ]) ?>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold" style="color: #4a0814;">Motivo o Justificación del Cambio:</label>
                    <?= Html::textInput('motivo', '', [
                        'class' => 'form-control',
                        'placeholder' => 'Ej. Documentación validada y sellada con éxito.',
                        'required' => true,
                    ]) ?>
                </div>
                <div class="col-md-2">
                    <?= Html::submitButton('Aplicar Cambio', [
                        'class' => 'btn w-100',
                        'style' => 'background: linear-gradient(135deg, #f3d27a 0%, #d4a638 100%); border: 1.5px solid #6b0f1a; color: #3a060f; font-weight: 700; border-radius: 8px; padding: 8px 14px; box-shadow: 0 4px 10px rgba(212, 166, 56, 0.25);',
                    ]) ?>
                </div>
            <?= Html::endForm() ?>
        </div>
    </div>

    <!-- DETALLE DEL EXPEDIENTE -->
    <div class="card mb-4" style="border: 1px solid rgba(107, 15, 26, 0.2); border-radius: 12px; overflow: hidden;">
        <div class="card-header text-white fw-bold" style="background: linear-gradient(90deg, #4a0814 0%, #6b0f1a 100%); border-bottom: 2px solid #c59b27; padding: 12px 18px;">
            Información del Alumno y Solicitud
        </div>
        <div class="card-body p-0">
            <?= DetailView::widget([
                'model' => $model,
                'options' => ['class' => 'table table-hover mb-0 detail-view'],
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
                            return '<span class="badge fs-6" style="background: linear-gradient(135deg, #6b0f1a 0%, #4a0814 100%); color: #f3d27a; border: 1px solid #c59b27; padding: 6px 14px; border-radius: 50px;">' . Html::encode($model->etapaActual->nombre ?? '-') . '</span>';
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
    <div class="card" style="border: 1px solid rgba(107, 15, 26, 0.2); border-radius: 12px; overflow: hidden;">
        <div class="card-header text-white fw-bold" style="background: linear-gradient(90deg, #3a060f 0%, #5a0b15 100%); border-bottom: 2px solid #c59b27; padding: 12px 18px;">
            Bitácora de Auditoría (Historial Inmutable de Cambios)
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover mb-0 text-center align-middle">
                    <thead>
                        <tr style="background: linear-gradient(90deg, #5a0b15 0%, #73111d 100%); color: #ffffff; border-bottom: 2px solid #c59b27;">
                            <th style="background: transparent; color: #f3d27a; padding: 12px 10px;">#</th>
                            <th style="background: transparent; color: #ffffff; padding: 12px 10px;">Etapa Anterior</th>
                            <th style="background: transparent; color: #ffffff; padding: 12px 10px;">Etapa Nueva</th>
                            <th style="background: transparent; color: #ffffff; padding: 12px 10px;">Responsable</th>
                            <th style="background: transparent; color: #ffffff; padding: 12px 10px;">Motivo / Justificación</th>
                            <th style="background: transparent; color: #ffffff; padding: 12px 10px;">Dirección IP</th>
                            <th style="background: transparent; color: #ffffff; padding: 12px 10px;">Fecha y Hora</th>
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
                                    <td class="fw-bold" style="color: #4a0814;"><?= $index + 1 ?></td>
                                    <td><span class="badge bg-secondary"><?= Html::encode($item->etapaAnterior->nombre ?? 'Inicial / Ninguna') ?></span></td>
                                    <td><span class="badge" style="background: linear-gradient(135deg, #6b0f1a 0%, #4a0814 100%); color: #f3d27a; border: 1px solid #c59b27;"><?= Html::encode($item->etapaNueva->nombre ?? '-') ?></span></td>
                                    <td><strong style="color: #4a0814;"><?= Html::encode($item->usuarioResponsable->username ?? 'ID: ' . $item->usuario_responsable_id) ?></strong></td>
                                    <td class="text-start"><?= Html::encode($item->motivo_cambio) ?></td>
                                    <td><code style="color: #6b0f1a; background-color: #faf3f4; padding: 3px 7px; border-radius: 5px;"><?= Html::encode($item->ip_origen) ?></code></td>
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