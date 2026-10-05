<?php
 
use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\PermisosHelpers;
 
/**
 * @var \yii\web\View $this
 * @var \common\models\User $model
 */
 
$this->title = $model->username;
$show_this_nav = PermisosHelpers::requerirMinimoRol('SuperUsuario');
 
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-view">
 
    <h1><?= Html::encode($this->title) ?></h1>
 
     <p>
 
<?php if (!Yii::$app->user->isGuest && $show_this_nav) {
      echo Html::a('Update', ['update', 'id' => $model->id], 
                             ['class' => 'btn btn-primary']);}?>
 
<?php if (!Yii::$app->user->isGuest && $show_this_nav) {
      echo Html::a('Delete', ['delete', 'id' => $model->id], [
         'class' => 'btn btn-danger',
         'data' => [
'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
         'method' => 'post',
         ],
        ]);}?>
 
   </p>
 
   <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            ['attribute'=>'profileLink', 'format'=>'raw'],
            
            //'username',
            //'auth_key',
            //'password_hash',
            //'password_reset_token',
            'email:email',
            'rolNombre',
            'estadoNombre',
            'tipoUsuarioNombre',
            'created_at',
            'updated_at',
            'id',
        ],
    ]) ?>
 
</div>
 
</div>
