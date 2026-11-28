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
        'label' => 'Reportar falla',
        'url' => ['/site/reportar-falla'],
    ],
    [
        'label' => 'Estatus',
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
        'label' => 'Cerrar sesión (' . Html::encode(Yii::$app->user->identity?->username) . ')',
        'url' => ['/site/logout'],
        'linkOptions' => [
            'data-method' => 'post',
            'class' => 'logout',
        ],
        'visible' => !Yii::$app->user->isGuest,
    ],
];

?>
<style>
    .nav-uno.navbar {
        background: linear-gradient(90deg, #962646 0%, #b4385c 52%, #9d294b 100%) !important;
        border-bottom: 2px solid #e6b447;
        box-shadow: 0 6px 18px rgba(84, 18, 37, 0.22);
        padding-top: 0.55rem;
        padding-bottom: 0.55rem;
    }

    .nav-uno .container,
    .nav-uno .container-fluid {
        gap: 0.55rem;
    }

    .nav-uno .navbar-brand {
        color: #ffffff !important;
        font-weight: 700;
        letter-spacing: 0.04em;
        padding: 0.38rem 0.85rem;
        border-radius: 8px;
        border: 1px solid rgba(242, 198, 97, 0.65);
        background-color: rgba(255, 255, 255, 0.1);
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.22);
        transition: background-color 0.2s ease, border-color 0.2s ease, transform 0.2s ease, box-shadow 0.2s ease;
        white-space: nowrap;
    }

    .nav-uno .navbar-brand i {
        color: #f4c862;
        margin-left: 0.2rem;
        transition: transform 0.22s ease;
    }

    .nav-uno .navbar-brand:hover {
        background-color: rgba(255, 255, 255, 0.18);
        border-color: #f4c862;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(45, 9, 20, 0.22);
    }

    .nav-uno .navbar-brand:hover i {
        transform: rotate(12deg);
    }

    .nav-uno .navbar-nav {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 0.4rem;
    }

    .nav-uno .nav-item .nav-link {
        color: #ffffff !important;
        font-size: 0.9rem;
        font-weight: 500;
        padding: 0.44rem 0.86rem !important;
        border-radius: 8px;
        border: 1px solid rgba(255, 255, 255, 0.2);
        background-color: rgba(255, 255, 255, 0.08);
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.16);
        transition: background-color 0.2s ease, border-color 0.2s ease, color 0.2s ease, transform 0.18s ease, box-shadow 0.2s ease;
        white-space: nowrap;
    }

    .nav-uno .nav-item .nav-link:hover,
    .nav-uno .nav-item .nav-link:focus {
        color: #fff8e6 !important;
        background-color: rgba(255, 255, 255, 0.18);
        border-color: #f4c862;
        transform: translateY(-1.5px);
        box-shadow: 0 5px 12px rgba(56, 11, 24, 0.2);
    }

    .nav-uno .nav-item .nav-link:active {
        transform: translateY(0) scale(0.98);
    }

    .nav-uno .nav-item .nav-link.active {
        color: #2b0f17 !important;
        background: linear-gradient(180deg, #f8d479 0%, #e7b546 100%);
        border-color: #fff0c2;
        font-weight: 600;
        box-shadow: 0 4px 12px rgba(244, 200, 98, 0.35);
    }

    .nav-uno .nav-item .nav-link.logout {
        border-color: rgba(244, 200, 98, 0.65);
        background-color: rgba(58, 10, 24, 0.36);
        color: #ffebb8 !important;
    }

    .nav-uno .nav-item .nav-link.logout:hover {
        background-color: rgba(58, 10, 24, 0.56);
        border-color: #f4c862;
        color: #ffffff !important;
    }

    .nav-uno #theme-toggle {
        width: 38px;
        height: 38px;
        padding: 0 !important;
        border-radius: 8px;
        border: 1px solid rgba(244, 200, 98, 0.58);
        background-color: rgba(255, 255, 255, 0.1);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        transition: background-color 0.2s ease, border-color 0.2s ease, transform 0.22s ease;
        flex-shrink: 0;
    }

    .nav-uno #theme-toggle:hover {
        background-color: rgba(255, 255, 255, 0.2);
        border-color: #f4c862;
        transform: translateY(-1px) rotate(12deg);
    }

    .nav-uno .navbar-toggler {
        border-color: rgba(244, 200, 98, 0.7);
        background-color: rgba(255, 255, 255, 0.1);
        border-radius: 8px;
    }

    .nav-uno .navbar-toggler:focus {
        box-shadow: 0 0 0 0.2rem rgba(244, 200, 98, 0.32);
    }

    @media (max-width: 1199.98px) {
        .nav-uno .nav-item .nav-link {
            padding: 0.4rem 0.68rem !important;
            font-size: 0.85rem;
        }
    }

    @media (max-width: 767.98px) {
        .nav-uno .navbar-collapse {
            margin-top: 0.55rem;
            padding: 0.7rem;
            border-radius: 10px;
            background-color: #841f3c;
            border: 1px solid rgba(244, 200, 98, 0.5);
            box-shadow: 0 10px 22px rgba(42, 8, 18, 0.28);
        }

        .nav-uno .navbar-nav {
            flex-direction: column;
            align-items: stretch;
            gap: 0.42rem;
        }

        .nav-uno .nav-item .nav-link {
            width: 100%;
            white-space: normal;
        }

        .nav-uno #theme-toggle {
            margin-top: 0.45rem;
            width: 100%;
        }
    }
</style>
<header id="header">
    <?php NavBar::begin(
        [
            'brandLabel' => 'UNO <i class="fa fa-plug"></i>',
            'brandUrl' => Yii::$app->homeUrl,
            'options' => ['class' => 'navbar-expand-md navbar-dark fixed-top nav-uno']
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