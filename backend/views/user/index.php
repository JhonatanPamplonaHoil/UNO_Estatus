<?php
 
use yii\helpers\Html;
use yii\grid\GridView;
use \yii\bootstrap5\Accordion;
 
/**
 * @var yii\web\View $this
 * @var backend\models\search\UserSearch $searchModel
 * @var yii\data\ActiveDataProvider $dataProvider
 */
 
$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">
 
    <h1><?= Html::encode($this->title) ?></h1>
 
<?php   echo Accordion::widget([
 
     
   'items' => [
        // equivalente a lo de arriba
        [
            'label' => 'Search',
            'content' => $this->render('_search', ['model' => $searchModel]) ,
            // open its content by default
            //'contentOptions' => ['class' => 'in']
        ],
        
   ] 
]);
  
   
?>
    
 
   <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
 
            //'id',
            ['attribute'=>'userIdLink', 'format'=>'raw'],
            ['attribute'=>'userLink', 'format'=>'raw'],
            ['attribute'=>'perfilLink', 'format'=>'raw'],
           
            'email:email',
            'rolNombre',
            'tipoUsuarioNombre',
            'estadoNombre',
            'created_at',
          
           ['class' => 'yii\grid\ActionColumn'],
           
            
            // 'updated_at',
 
            
        ],
    ]); ?>
 
</div>