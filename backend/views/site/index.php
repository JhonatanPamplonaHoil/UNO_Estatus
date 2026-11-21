<?php

use yii\helpers\Html;
use common\models\PermisosHelpers;

/** 
 * @var yii\web\View $this 
 */

$this->title = 'Admin Yii 2 Build';

// Inicializar la variable de forma segura para evitar errores si el usuario no ha iniciado sesión
$es_admin = false;
if (!Yii::$app->user->isGuest) {
    $es_admin = PermisosHelpers::requerirMinimoRol('Admin');
}
?>

<style>
    .uno-admin-hero {
        background-image: 
            linear-gradient(135deg, rgba(110, 14, 38, 0.90) 0%, rgba(145, 20, 51, 0.86) 100%),
            url('<?= Yii::getAlias('@web') ?>/images/fondo.png');
        background-size: cover;
        background-position: center;
        color: #ffffff;
        border-radius: 16px;
        box-shadow: 0 10px 25px rgba(110, 14, 38, 0.22);
    }
    .uno-logo-box {
        background-color: #ffffff;
        border-radius: 14px;
        padding: 16px 26px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.18);
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    .uno-logo-box img {
        max-height: 85px;
        width: auto;
        object-fit: contain;
    }
    .btn-uno-dorado {
        background-color: #ffc107;
        border-color: #ffc107;
        color: #1f1f1f;
        font-weight: 700;
        transition: all 0.2s ease;
    }
    .btn-uno-dorado:hover {
        background-color: #e0a800;
        border-color: #d39e00;
        color: #1f1f1f;
        transform: translateY(-2px);
    }
    .uno-card {
        background-color: #ffffff;
        border: 1px solid #eaeaea;
        border-top: 4px solid #7a122c;
        border-radius: 14px;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.05);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        height: 100%;
    }
    .uno-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 22px rgba(122, 18, 44, 0.14);
    }
    .uno-card h2 {
        color: #6e0e26;
        font-size: 1.4rem;
        font-weight: 700;
    }
    .btn-uno-guinda {
        color: #7a122c;
        border-color: #7a122c;
        font-weight: 600;
        transition: all 0.2s ease;
    }
    .btn-uno-guinda:hover {
        background-color: #7a122c;
        border-color: #7a122c;
        color: #ffffff;
    }
</style>

<div class="site-index py-2">

    <div class="uno-admin-hero p-4 p-md-5 mb-5">
        <div class="container-fluid py-2">
            <div class="row align-items-center g-4">
                <div class="col-lg-8 text-center text-lg-start">
                    <h1 class="display-5 fw-bold mb-3">¡Bienvenido a Admin!</h1>
                    <p class="fs-4 mb-4" style="opacity: 0.92;">
                        Ahora puede administrar usuarios, roles, y más con nuestras sencillas herramientas.
                    </p>
                    <p class="mb-0">
                        <?php
                        if (!Yii::$app->user->isGuest && $es_admin) {
                            echo Html::a('Administrar Usuarios', ['user/index'], ['class' => 'btn btn-lg btn-uno-dorado px-4 py-2 rounded-3 shadow-sm']);
                        }
                        ?>
                    </p>
                </div>
                <div class="col-lg-4 text-center text-lg-end">
                    <div class="uno-logo-box">
                        <?= Html::img('@web/images/UnoiM.png', ['alt' => 'Logo UNO']) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="body-content">
        <div class="row g-4 mb-4">
            <div class="col-lg-4">
                <div class="uno-card p-4 d-flex flex-column justify-content-between">
                    <div>
                        <h2 class="mb-3">Usuarios</h2>
                        <p class="text-muted">
                            Este es el lugar para administrar usuarios. Puede editar estados y roles desde aquí.
                            La IU es fácil de usar e intuitiva, simplemente haga clic en el link de abajo para comenzar.
                        </p>
                    </div>
                    <p class="mb-0 mt-3">
                        <?php
                        if (!Yii::$app->user->isGuest && $es_admin) {
                            echo Html::a('Administrar Usuarios', ['user/index'], ['class' => 'btn btn-uno-guinda w-100 rounded-3']);
                        }
                        ?>
                    </p>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="uno-card p-4 d-flex flex-column justify-content-between">
                    <div>
                        <h2 class="mb-3">Roles</h2>
                        <p class="text-muted">
                            Aquí es donde administra Roles. Puede decidir quién es admin y quién no. Puede
                            agregar un nuevo rol si lo desea, simplemente haciendo clic en el link de abajo para comenzar.
                        </p>
                    </div>
                    <p class="mb-0 mt-3">
                        <?php
                        if (!Yii::$app->user->isGuest && $es_admin) {
                            echo Html::a('Administrar Roles', ['rol/index'], ['class' => 'btn btn-uno-guinda w-100 rounded-3']);
                        }
                        ?>
                    </p>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="uno-card p-4 d-flex flex-column justify-content-between">
                    <div>
                        <h2 class="mb-3">Perfiles</h2>
                        <p class="text-muted">
                            ¿Necesita revisar Perfiles? Este es el lugar para hacerlo.
                            Estos son fáciles de administrar via IU. Simplemente haga clic en el link de abajo para
                            administrar perfiles.
                        </p>
                    </div>
                    <p class="mb-0 mt-3">
                        <?php
                        if (!Yii::$app->user->isGuest && $es_admin) {
                            echo Html::a('Administrar Perfiles', ['perfil/index'], ['class' => 'btn btn-uno-guinda w-100 rounded-3']);
                        }
                        ?>
                    </p>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-4">
                <div class="uno-card p-4 d-flex flex-column justify-content-between">
                    <div>
                        <h2 class="mb-3">Tipos de Usuario</h2>
                        <p class="text-muted">
                            Este es el lugar para administrar tipos de usuario. Puede editar tipos
                            de usuario desde aquí. La IU es fácil de usar e intuitiva, simplemente
                            haga clic en el link de abajo para comenzar.
                        </p>
                    </div>
                    <p class="mb-0 mt-3">
                        <?php
                        if (!Yii::$app->user->isGuest && $es_admin) {
                            echo Html::a('Administrar Tipos de Usuario', ['tipo-usuario/index'], ['class' => 'btn btn-uno-guinda w-100 rounded-3']);
                        }
                        ?>
                    </p>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="uno-card p-4 d-flex flex-column justify-content-between">
                    <div>
                        <h2 class="mb-3">Estados</h2>
                        <p class="text-muted">
                            Aquí es donde administra Estados. Puede agregar o eliminar.
                            Puede añadir nuevos estados si lo desea, simplemente haga clic en el link
                            de abajo para comenzar.
                        </p>
                    </div>
                    <p class="mb-0 mt-3">
                        <?php
                        if (!Yii::$app->user->isGuest && $es_admin) {
                            echo Html::a('Administrar Estados', ['estado/index'], ['class' => 'btn btn-uno-guinda w-100 rounded-3']);
                        }
                        ?>
                    </p>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="uno-card p-4 d-flex flex-column justify-content-between">
                    <div>
                        <h2 class="mb-3">Placeholder</h2>
                        <p class="text-muted">
                            ¿Necesita revisar Perfiles? Este es el lugar para hacerlo.
                            Estos son fáciles de administrar via IU. Simplemente haga clic en el link de abajo
                            para administrar perfiles.
                        </p>
                    </div>
                    <p class="mb-0 mt-3">
                        <?php
                        if (!Yii::$app->user->isGuest && $es_admin) {
                            echo Html::a('Administrar Perfiles', ['perfil/index'], ['class' => 'btn btn-uno-guinda w-100 rounded-3']);
                        }
                        ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>