<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\Rol $model */

$this->title = 'Create Rol';
$this->params['breadcrumbs'][] = ['label' => 'Rols', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<style>
    .rol-create .rc-banner {
        background: linear-gradient(90deg, #6b0f1a 0%, #7d121f 100%);
        color: #ffffff;
        border-radius: 14px;
        padding: 20px 26px;
        box-shadow: 0 8px 20px rgba(107, 15, 26, 0.16);
    }

    .rol-create .rc-banner-sub {
        color: rgba(255, 255, 255, 0.86);
        font-size: 0.9rem;
    }

    .rol-create .rc-card {
        background-color: #ffffff;
        border: 1px solid rgba(107, 15, 26, 0.13);
        border-top: 4px solid #6b0f1a;
        border-radius: 14px;
        box-shadow: 0 10px 25px rgba(107, 15, 26, 0.08);
    }
</style>

<div class="rol-create">
    <div class="rc-banner mb-4">
        <h1 class="h3 fw-bold mb-1"><?= Html::encode($this->title) ?></h1>
        <div class="rc-banner-sub">Registra un nuevo rol para administrar los permisos del sistema.</div>
    </div>

    <div class="card rc-card border-0 rounded-3">
        <div class="card-body p-4">
            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>
        </div>
    </div>
</div>
