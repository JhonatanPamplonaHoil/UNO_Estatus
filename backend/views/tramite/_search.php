<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\search\TramiteTitulacionSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="tramite-titulacion-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'folio') ?>

    <?= $form->field($model, 'user_id') ?>

    <?= $form->field($model, 'carrera_id') ?>

    <?= $form->field($model, 'modalidad_id') ?>

    <?php // echo $form->field($model, 'etapa_actual_id') ?>

    <?php // echo $form->field($model, 'matricula') ?>

    <?php // echo $form->field($model, 'promedio_general') ?>

    <?php // echo $form->field($model, 'observaciones') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
