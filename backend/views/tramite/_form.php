<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\TramiteTitulacion $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="tramite-titulacion-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'folio')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'user_id')->textInput() ?>

    <?= $form->field($model, 'carrera_id')->textInput() ?>

    <?= $form->field($model, 'modalidad_id')->textInput() ?>

    <?= $form->field($model, 'etapa_actual_id')->textInput() ?>

    <?= $form->field($model, 'matricula')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'promedio_general')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'observaciones')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
