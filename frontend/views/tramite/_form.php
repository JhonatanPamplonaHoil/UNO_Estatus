<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use common\models\Carrera;
use common\models\ModalidadTitulacion;

/** @var yii\web\View $this */
/** @var common\models\TramiteTitulacion $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="tramite-titulacion-form card shadow-sm p-4">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'matricula')->textInput([
                'maxlength' => true,
                'placeholder' => 'Ej. 22070081'
            ]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'carrera_id')->dropDownList(
                ArrayHelper::map(Carrera::find()->all(), 'id', 'nombre'),
                ['prompt' => '-- Selecciona tu Carrera --']
            ) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <?= $form->field($model, 'modalidad_id')->dropDownList(
                ArrayHelper::map(ModalidadTitulacion::find()->where(['activo' => 1])->all(), 'id', 'nombre'),
                ['prompt' => '-- Selecciona tu Modalidad de Titulación --']
            ) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'promedio_general')->textInput([
                'maxlength' => true,
                'placeholder' => 'Ej. 95.50'
            ]) ?>
        </div>
    </div>

    <?= $form->field($model, 'observaciones')->textarea([
        'rows' => 4,
        'placeholder' => 'Información adicional sobre tu trámite, tema de informe/tesis o especificaciones...'
    ]) ?>

    <div class="form-group mt-3">
        <?= Html::submitButton('Registrar Solicitud de Trámite', ['class' => 'btn btn-success px-4']) ?>
        <?= Html::a('Cancelar', ['index'], ['class' => 'btn btn-outline-secondary ms-2']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
