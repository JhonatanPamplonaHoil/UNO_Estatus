<?php

declare(strict_types=1);

/** @var yii\web\View $this */

use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;
use yii\helpers\Html;

$items = [
    [
        'label' => 'Inicio',
        'url' => ['/site/index'],
    ],
    [
        'label' => 'About',
        'url' => ['/site/about'],
    ],
    [
        'label' => 'Contact',
        'url' => ['/site/contact'],
    ],
    [
        'label' => 'Mis trámites',
        'url' => ['/tramite/index'],
        'visible' => !Yii::$app->user->isGuest,
    ],
    [
        'label' => 'Agendar cita', 
        'url' => ['/cita-entrega/agendar'], 
        'visible' => !Yii::$app->user->isGuest],
    [
        'label' => 'Perfil',
        'url' => ['/perfil/view'],
        'visible' => !Yii::$app->user->isGuest,
    ],
    [
        'label' => 'Crear cuenta',
        'url' => ['/site/signup'],
        'visible' => Yii::$app->user->isGuest,
    ],
    [
        'label' => 'Iniciar sesión',
        'url' => ['/site/login'],
        'visible' => Yii::$app->user->isGuest,
    ],
    [
        'label' => 'Salir (' . Html::encode(Yii::$app->user->identity?->username) . ')',
        'url' => ['/site/logout'],
        'linkOptions' => [
            'data-method' => 'post',
            'class' => 'logout',
        ],
        'visible' => !Yii::$app->user->isGuest,
    ],
];

?>
<header id="header">
    <?php NavBar::begin(
        [
            'brandLabel' => 'UNO <i class="fa fa-plug"></i>',
            'brandUrl' => Yii::$app->homeUrl,
            'options' => ['class' => 'navbar-expand-md navbar-dark bg-dark fixed-top']
        ],
    ) ?>
    <?= Nav::widget(
        [
            'options' => ['class' => 'navbar-nav me-auto'],
            'encodeLabels' => false,
            'items' => $items,
        ],
    ) ?>
    <?= Html::button(
        '&#127769;',
        [
            'id' => 'theme-toggle',
            'class' => 'btn btn-link nav-link fs-5',
            'aria-label' => 'Switch to dark mode',
        ],
    ) ?>
    <?php NavBar::end() ?>
</header>