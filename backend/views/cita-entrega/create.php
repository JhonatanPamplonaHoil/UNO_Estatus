<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\CitaEntrega $model */

$this->title = 'Create Cita Entrega';
$this->params['breadcrumbs'][] = ['label' => 'Cita Entregas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cita-entrega-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
