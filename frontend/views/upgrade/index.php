<?php

use yii\helpers\Html;

/** @var \yii\web\View $this */
/** @var \frontend\models\Perfil $persona */
?>
<h1>Ey <?= Html::encode($persona->nombre) ?>, Esto Requiere un Upgrade</h1>

<p>
    Puede obtener el acceso que desea realizando un upgrade, pero <?= Html::encode($persona->nombre) ?>,
    eso no es todo. Podrá ir a cualquier lugar, ¿no es eso genial?
</p>