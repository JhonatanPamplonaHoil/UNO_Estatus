<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\TramiteTitulacion $model */

$this->title = 'Update Tramite Titulacion: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Tramite Titulacions', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="tramite-titulacion-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
