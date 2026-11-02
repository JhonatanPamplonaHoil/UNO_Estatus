<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\DisponibilidadCita $model */

$this->title = 'Create Disponibilidad Cita';
$this->params['breadcrumbs'][] = ['label' => 'Disponibilidad Citas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="disponibilidad-cita-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
