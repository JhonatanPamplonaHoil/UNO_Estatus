<?php

declare(strict_types=1);

/** @var yii\web\View $this */
/** @var string $content */

use common\widgets\Alert;
use yii\helpers\Html;
use yii\helpers\Url;
use frontend\assets\FontAwesomeAsset;

$this->render('_head');

$controllerId = Yii::$app->controller->id ?? '';
$actionId = Yii::$app->controller->action->id ?? '';
$subActions = ['create', 'update', 'edit', 'view', 'detalle'];
$showBackButton = in_array($actionId, $subActions, true) && $controllerId !== '';
$backUrl = $controllerId !== '' ? Url::to([$controllerId . '/index']) : Url::home();
$bgImageUrl = Url::to('@web/images/ffondo.jpg');
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100" data-bs-theme="light">
<head>
    <?php $this->head() ?>
    <title><?= Html::encode($this->title) ?></title>
    <style>
        :root {
            --uno-wine-light: #9e2a4b;
            --uno-wine-mid: #b5375b;
            --uno-wine-soft: rgba(158, 42, 75, 0.12);
            --uno-gold-soft: #e9be58;
            --uno-gold-glow: rgba(233, 190, 88, 0.38);
            --uno-surface: #fdf9fa;
            --uno-surface-card: rgba(255, 255, 255, 0.94);
            --uno-border-soft: rgba(158, 42, 75, 0.2);
            --uno-text-main: #2d141c;
        }

        body {
            position: relative;
            min-height: 100vh;
            background-color: var(--uno-surface);
            color: var(--uno-text-main);
            overflow-x: hidden;
        }

        /* Capa de fondo fija y adaptable sin deformar al achicar la ventana */
        body::before {
            content: "";
            position: fixed;
            inset: 0;
            z-index: -2;
            background-image: url("<?= Html::encode($bgImageUrl) ?>");
            background-size: cover;
            background-position: center center;
            background-repeat: no-repeat;
            transform: translateZ(0);
            pointer-events: none;
        }

        /* Velo suave para que no compita con el contenido ni el menú */
        body::after {
            content: "";
            position: fixed;
            inset: 0;
            z-index: -1;
            background: linear-gradient(
                180deg,
                rgba(253, 249, 250, 0.72) 0%,
                rgba(248, 236, 240, 0.78) 100%
            );
            pointer-events: none;
        }

        .uno-main-shell {
            margin-top: 96px;
            padding-bottom: 4.5rem;
            transition: margin-top 0.25s ease, padding 0.25s ease;
        }

        .uno-main-shell .container {
            max-width: 1240px;
        }

        .uno-main-shell .card,
        .uno-main-shell .box,
        .uno-main-shell .panel {
            background-color: var(--uno-surface-card);
            border: 1px solid var(--uno-border-soft);
            border-radius: 14px;
            box-shadow: 0 8px 22px rgba(158, 42, 75, 0.08);
        }

        /* Botón regresar flotante en una sola posición fija */
        .uno-btn-back-floating {
            position: fixed;
            left: 24px;
            bottom: 24px;
            z-index: 1040;
            display: inline-flex;
            align-items: center;
            gap: 0.55rem;
            padding: 0.65rem 1.2rem;
            border-radius: 999px;
            font-weight: 600;
            font-size: 0.92rem;
            text-decoration: none;
            color: #ffffff;
            background: linear-gradient(135deg, var(--uno-wine-mid) 0%, var(--uno-wine-light) 100%);
            border: 1px solid rgba(233, 190, 88, 0.55);
            box-shadow: 0 10px 24px rgba(158, 42, 75, 0.28);
            transition: transform 0.22s cubic-bezier(0.22, 1, 0.36, 1),
                        box-shadow 0.22s ease,
                        border-color 0.22s ease,
                        background 0.22s ease;
            overflow: hidden;
        }

        .uno-btn-back-floating::before {
            content: "";
            position: absolute;
            top: 0;
            left: -115%;
            width: 75%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.25), transparent);
            transition: left 0.45s ease;
        }

        .uno-btn-back-floating:hover::before {
            left: 130%;
        }

        .uno-btn-back-floating:hover {
            transform: translateY(-3px) translateX(-2px);
            background: linear-gradient(135deg, #c44267 0%, var(--uno-wine-mid) 100%);
            border-color: var(--uno-gold-soft);
            box-shadow: 0 14px 28px var(--uno-gold-glow);
            color: #ffffff;
        }

        .uno-btn-back-floating:active {
            transform: translateY(0) scale(0.97);
        }

        .uno-btn-back-floating .uno-back-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 24px;
            height: 24px;
            border-radius: 999px;
            background: rgba(233, 190, 88, 0.24);
            color: var(--uno-gold-soft);
            font-size: 0.88rem;
            line-height: 1;
            transition: transform 0.22s ease, background 0.22s ease, color 0.22s ease;
        }

        .uno-btn-back-floating:hover .uno-back-icon {
            transform: translateX(-3px);
            background: var(--uno-gold-soft);
            color: #2b1118;
        }

        /* Botones generales dentro del contenido */
        .uno-main-shell .btn {
            border-radius: 10px;
            font-weight: 600;
            letter-spacing: 0.01em;
            padding: 0.55rem 1.15rem;
            transition: all 0.22s cubic-bezier(0.22, 1, 0.36, 1);
            position: relative;
            overflow: hidden;
        }

        .uno-main-shell .btn:hover:not(:disabled):not(.disabled) {
            transform: translateY(-2px);
            box-shadow: 0 8px 18px rgba(158, 42, 75, 0.16);
        }

        .uno-main-shell .btn:active:not(:disabled):not(.disabled) {
            transform: translateY(0) scale(0.98);
        }

        .uno-main-shell .btn-primary {
            background: linear-gradient(135deg, var(--uno-wine-mid) 0%, var(--uno-wine-light) 100%);
            border: 1px solid rgba(233, 190, 88, 0.45);
            color: #ffffff;
        }

        .uno-main-shell .btn-primary:hover,
        .uno-main-shell .btn-primary:focus {
            background: linear-gradient(135deg, #c44267 0%, var(--uno-wine-mid) 100%);
            border-color: var(--uno-gold-soft);
            box-shadow: 0 8px 20px var(--uno-gold-glow);
            color: #ffffff;
        }

        .uno-main-shell .btn-warning {
            background: linear-gradient(135deg, #f3cb6b 0%, var(--uno-gold-soft) 100%);
            border: 1px solid rgba(158, 42, 75, 0.22);
            color: #2b1118;
        }

        .uno-main-shell .btn-warning:hover,
        .uno-main-shell .btn-warning:focus {
            background: linear-gradient(135deg, #fad67d 0%, #e5b646 100%);
            border-color: var(--uno-wine-light);
            box-shadow: 0 8px 20px rgba(233, 190, 88, 0.42);
            color: #240d13;
        }

        .uno-main-shell .btn-outline-primary,
        .uno-main-shell .btn-outline-secondary {
            border-color: var(--uno-border-soft);
            color: var(--uno-wine-light);
            background-color: var(--uno-surface-card);
        }

        .uno-main-shell .btn-outline-primary:hover,
        .uno-main-shell .btn-outline-secondary:hover {
            background-color: var(--uno-wine-soft);
            border-color: var(--uno-wine-light);
            color: var(--uno-wine-light);
        }

        .uno-main-shell .table-responsive {
            background-color: var(--uno-surface-card);
            border-radius: 12px;
            box-shadow: 0 4px 16px rgba(158, 42, 75, 0.08);
        }

        @media (max-width: 991.98px) {
            .uno-main-shell {
                margin-top: 84px;
            }
        }

        @media (max-width: 575.98px) {
            body::before {
                background-position: center top;
            }

            .uno-main-shell {
                margin-top: 78px;
                padding-bottom: 5.25rem;
            }

            .uno-main-shell .container {
                padding-left: 0.9rem;
                padding-right: 0.9rem;
            }

            .uno-btn-back-floating {
                left: 14px;
                bottom: 14px;
                padding: 0.58rem 1rem;
                font-size: 0.88rem;
            }

            .uno-main-shell .btn {
                width: 100%;
                justify-content: center;
            }

            .uno-main-shell .btn-group .btn,
            .uno-main-shell .input-group .btn,
            .uno-main-shell .pagination .page-link {
                width: auto;
            }
        }
    </style>
</head>
<body class="d-flex flex-column h-100">
<?php $this->beginBody() ?>

<?= $this->render('_header') ?>

<main id="main" class="flex-grow-1 uno-main-shell" role="main">
    <div class="container">
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</main>

<?php if ($showBackButton): ?>
    <a href="<?= Html::encode($backUrl) ?>" class="uno-btn-back-floating" aria-label="Regresar">
        <span class="uno-back-icon" aria-hidden="true">&#8592;</span>
        <span>Regresar</span>
    </a>
<?php endif ?>

<?= $this->render('_footer') ?>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>