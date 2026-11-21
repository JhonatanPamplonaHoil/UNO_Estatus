<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\PermisosHelpers;

/**
 * @var yii\web\View $this
 * @var frontend\models\Perfil $model
 */

$this->title = "Perfil de " . $model->user->username;
$this->params['breadcrumbs'][] = ['label' => 'Perfiles', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<style>
/* Estilos Institucionales del Sistema UNO */
.perfil-card {
    border: none;
    border-radius: 1.25rem;
    box-shadow: 0 15px 35px rgba(92, 6, 32, 0.12), 0 5px 15px rgba(0, 0, 0, 0.04);
    overflow: hidden;
    background-color: #ffffff;
    max-width: 900px;
    margin: 0 auto;
}

.perfil-header {
    background: linear-gradient(135deg, #4a041a 0%, #7d0d30 100%);
    color: #ffffff;
    padding: 2rem;
    border-bottom: 3px solid #5c0620;
}

.btn-uno-primary {
    background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
    color: #ffffff !important;
    border: none;
    font-weight: 600;
    padding: 0.55rem 1.25rem;
    border-radius: 0.6rem;
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
    transition: all 0.25s ease;
    text-decoration: none;
}

.btn-uno-primary:hover {
    background: linear-gradient(135deg, #1d4ed8 0%, #1e40af 100%);
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(37, 99, 235, 0.4);
}

.btn-uno-danger {
    background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
    color: #ffffff !important;
    border: none;
    font-weight: 600;
    padding: 0.55rem 1.25rem;
    border-radius: 0.6rem;
    box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
    transition: all 0.25s ease;
    text-decoration: none;
}

.btn-uno-danger:hover {
    background: linear-gradient(135deg, #b91c1c 0%, #991b1b 100%);
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(220, 38, 38, 0.4);
}

/* Personalización de la Tabla DetailView */
.custom-detail-table {
    margin-bottom: 0;
}

.custom-detail-table th {
    width: 35%;
    background-color: #f8fafc;
    color: #475569;
    font-weight: 700;
    font-size: 0.88rem;
    padding: 1rem 1.25rem;
    border-color: #e2e8f0;
    vertical-align: middle;
}

.custom-detail-table td {
    color: #1e293b;
    font-size: 0.95rem;
    padding: 1rem 1.25rem;
    border-color: #e2e8f0;
    vertical-align: middle;
}

.custom-detail-table tr:hover td {
    background-color: #fcf8f9;
}

.avatar-circle {
    width: 58px;
    height: 58px;
    background: rgba(255, 255, 255, 0.18);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.6rem;
    backdrop-filter: blur(4px);
    border: 1px solid rgba(255, 255, 255, 0.25);
}
</style>

<div class="perfil-view py-3">

    <div class="card perfil-card">
        <!-- Encabezado con degradado vino -->
        <div class="perfil-header d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="avatar-circle text-white">
                    👤
                </div>
                <div>
                    <h2 class="h3 fw-bold mb-1 text-white"><?= Html::encode($this->title) ?></h2>
                    <p class="mb-0 opacity-75 small">Información general del usuario</p>
                </div>
            </div>

            <div class="d-flex gap-2 align-items-center">
                <?php
                //esto no es necesario pero está aquí como ejemplo
                if (PermisosHelpers::userDebeSerPropietario('perfil', $model->id)) {
                    echo Html::a('Update', ['update', 'id' => $model->id],
                        ['class' => 'btn btn-primary btn-uno-primary']);
                } ?>

                <?= Html::a('Delete', ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger btn-uno-danger',
                    'data' => [
                        'confirm' => Yii::t('app', 'Are you sure to delete this item?'),
                        'method' => 'post',
                    ],
                ]) ?>
            </div>
        </div>

        <!-- Tabla de Detalle -->
        <div class="card-body p-0">
            <div class="table-responsive">
                <?= DetailView::widget([
                    'model' => $model,
                    'options' => ['class' => 'table table-striped custom-detail-table'],
                    'attributes' => [
                        //'id',
                        'user.username',
                        'nombre',
                        'apellido',
                        'fecha_nacimiento',
                        'genero.genero_nombre',
                        'created_at',
                        'updated_at',
                        //'user_id',
                    ],
                ]) ?>
            </div>
        </div>
    </div>

</div>