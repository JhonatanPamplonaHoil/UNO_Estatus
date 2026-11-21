<?php

use backend\assets\AppAsset;
use backend\assets\FontAwesomeAsset;
use common\models\PermisosHelpers;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;
use yii\helpers\Html;
use yii\bootstrap5\Breadcrumbs;

/**
 * @var \yii\web\View $this
 * @var string $content
 */

AppAsset::register($this);
FontAwesomeAsset::register($this);

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <link rel="icon" href="<?= Yii::getAlias('@web') ?>/images/logo.png" type="image/png">
    <?php $this->head() ?>
</head>
<body class="d-flex flex-column h-100">
<?php $this->beginBody() ?>

<header id="header">
    <?php
    // 1. Inicialización segura de roles
    $es_admin = false;
    $es_super_admin = false;

    if (!Yii::$app->user->isGuest) {
        $es_admin = PermisosHelpers::requerirMinimoRol('Admin');
        $es_super_admin = PermisosHelpers::requerirMinimoRol('SuperUsuario');
    }

    // 2. Título de la barra según el nivel de acceso
    if (!Yii::$app->user->isGuest && $es_super_admin) {
        $brandLabel = 'UNO Estatus <i class="fa fa-plug"></i> SuperAdmin';
    } elseif (!Yii::$app->user->isGuest && $es_admin) {
        $brandLabel = 'UNO Estatus <i class="fa fa-plug"></i> Admin';
    } else {
        $brandLabel = 'UNO <i class="fa fa-plug"></i>';
    }

    NavBar::begin([
        'brandLabel' => $brandLabel,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-expand-md navbar-dark bg-dark fixed-top',
        ],
    ]);

    // 3. Declaración global del menú
    $menuItems = [
        ['label' => 'Inicio', 'url' => ['site/index']],
    ];

    // Opciones operativas: visibles para Admin y SuperUsuario
    if (!Yii::$app->user->isGuest && $es_admin) {
        $menuItems[] = ['label' => 'Trámites', 'url' => ['tramite/index']];
        $menuItems[] = ['label' => 'Crear citas', 'url' => ['disponibilidad-cita/index']];
        $menuItems[] = ['label' => 'Citas Agendadas', 'url' => ['cita-entrega/index']];
    }

    // Opciones de configuración: exclusivas para SuperUsuario
    if (!Yii::$app->user->isGuest && $es_super_admin) {
        $menuItems[] = ['label' => 'Usuarios', 'url' => ['user/index']];
        $menuItems[] = ['label' => 'Perfiles', 'url' => ['perfil/index']];
        $menuItems[] = ['label' => 'Roles', 'url' => ['rol/index']];
        $menuItems[] = ['label' => 'Tipos de Usuario', 'url' => ['tipo-usuario/index']];
        $menuItems[] = ['label' => 'Estados', 'url' => ['estado/index']];
    }

    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => 'Login', 'url' => ['site/login']];
    } else {
        $menuItems[] = [
            'label' => 'Salir (' . Yii::$app->user->identity->username . ')',
            'url' => ['/site/logout'],
            'linkOptions' => [
                'data-method' => 'post',
                'class' => 'logout',
            ]
        ];
    }

    echo Nav::widget([
        'options' => ['class' => 'navbar-nav ms-auto'], // ms-auto empuja el menú a la derecha en Bootstrap 5
        'items' => $menuItems,
    ]);

    NavBar::end();
    ?>
</header>

<main id="main" class="flex-grow-1" role="main" style="margin-top: 60px;">
    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= $content ?>
    </div>
</main>

<footer class="footer mt-auto py-3 text-muted">
    <div class="container">
        <p class="float-start">&copy; UNO <?= date('Y') ?></p>
        <p class="float-end"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>