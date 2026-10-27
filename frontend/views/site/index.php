<?php

declare(strict_types=1);

/** @var yii\web\View $this */
use yii\bootstrap5\Modal;
use kartik\social\FacebookPlugin;
use yii\bootstrap5\Alert;
use yii\helpers\Html;
use yii\bootstrap5\Accordion;

$this->title = 'UNO Estatus - Sistema de Titulación';
$this->params['meta_description'] = 'Sistema de seguimiento de trámite de titulación para egresados de la Universidad de Oriente de Valladolid.';
$this->params['meta_keywords'] = 'uno, titulacion, tramite, egresados, valladolid, yii2';
?>
<div class="site-index">

    <!-- Banner Principal con Distribución de 2 Columnas -->
    <div class="p-5 mb-4 rounded-3 shadow-sm text-white position-relative overflow-hidden" 
         style="background: url('<?= Yii::getAlias('@web') ?>/images/fondo_banner.png') center/cover no-repeat; border-left: 8px solid #4a121f;">
        
        <div class="container-fluid py-2 position-relative" style="z-index: 2;">
            
            <div class="row align-items-center">
                <!-- Columna Izquierda: Título, Descripción y Botones -->
                <div class="col-lg-8">
                    <h1 class="display-6 fw-bold text-white mb-2" style="text-shadow: 0 2px 4px rgba(0,0,0,0.5);">
                        Sistema de Seguimiento de Titulación <i class="fa fa-graduation-cap ms-2"></i>
                    </h1>
                    <p class="fs-5 text-light mb-4" style="text-shadow: 0 1px 3px rgba(0,0,0,0.6);">
                        Plataforma oficial para la gestión y estatus de trámites de titulación de egresados de la Universidad de Oriente de Valladolid.
                    </p>
                    
                    <!-- Acciones de Usuario -->
                    <div class="d-flex align-items-center gap-3 flex-wrap">
                        <?php if (Yii::$app->user->isGuest): ?>
                            <?= Html::a('Comience Hoy', ['site/signup'], ['class' => 'btn btn-lg btn-light text-dark fw-bold shadow-sm']) ?>
                            <?= Html::a('Iniciar Sesión', ['site/login'], ['class' => 'btn btn-lg btn-outline-light shadow-sm']) ?>
                        <?php else: ?>
                            <span class="badge bg-light text-dark fs-6 p-2 shadow-sm">
                                Bienvenido, <?= Html::encode(Yii::$app->user->identity->username) ?>
                            </span>
                            <?= Html::a('Ver Mi Trámite', ['tramite/index'], ['class' => 'btn btn-lg btn-warning shadow-sm fw-bold']) ?>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Columna Derecha: Logo Institucional con Fondo Blanco -->
                <div class="col-lg-4 text-lg-end text-center mt-4 mt-lg-0">
                    <div class="bg-white p-3 rounded-3 shadow-sm d-inline-block">
                        <?= Html::img('@web/images/logo_uno.png', [
                            'alt' => 'Logo Universidad de Oriente', 
                            'style' => 'height: 65px; object-fit: contain;'
                        ]) ?>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Alerta Informativa -->
    <?= Alert::widget([
        'options' => [
            'class' => 'alert shadow-sm rounded-3 border-0 text-dark',
            'style' => 'background-color: #f4e8eb; border-left: 4px solid #6b1d2f !important;',
        ],
        'body' => '<i class="fa fa-info-circle me-2" style="color: #6b1d2f;"></i> Consulta de forma ágil el avance de tu expediente de titulación en tiempo real.',
    ]); ?>

    <!-- Etapas del Proceso de Titulación -->
    <div class="body-content my-4">
        <div class="row g-4">
            <div class="col-lg-4">
                <div class="card h-100 border-0 shadow-sm rounded-3 p-3">
                    <div class="card-body">
                        <h3 class="h4 fw-bold" style="color: #6b1d2f;"><i class="fa fa-file-text me-2"></i>1. Requisitos</h3>
                        <p class="text-secondary mt-3">
                            Inicia tu proceso adjuntando la documentación requerida para la revisión previa por el departamento escolar.
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card h-100 border-0 shadow-sm rounded-3 p-3">
                    <div class="card-body">
                        <h3 class="h4 fw-bold" style="color: #6b1d2f;"><i class="fa fa-line-chart me-2"></i>2. Seguimiento</h3>
                        <p class="text-secondary mt-3">
                            Monitorea los avances de cada etapa de tu trámite y recibe notificaciones cuando cambie el estatus de tu solicitud.
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card h-100 border-0 shadow-sm rounded-3 p-3">
                    <div class="card-body">
                        <h3 class="h4 fw-bold" style="color: #6b1d2f;"><i class="fa fa-certificate me-2"></i>3. Titulación</h3>
                        <p class="text-secondary mt-3">
                            Consulta la fecha asignada para el acto protocolario y la emisión final de tu título profesional.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Acordeón y Sección de Comentarios -->
    <div class="row my-4">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm p-3">
                <h5 class="fw-bold mb-3" style="color: #6b1d2f;">Información Adicional</h5>
                <?= Accordion::widget([
                    'items' => [
                        [
                            'label' => 'Características Principales',
                            'content' => FacebookPlugin::widget([
                                'type' => FacebookPlugin::SHARE,
                                'settings' => ['href' => 'http://www.yii2build.com', 'width' => '350']
                            ]),
                        ],
                        [
                            'label' => 'Recursos Principales',
                            'content' => FacebookPlugin::widget([
                                'type' => FacebookPlugin::SHARE,
                                'settings' => ['href' => 'http://www.yii2build.com', 'width' => '350']
                            ]),
                        ],
                    ]
                ]); ?>
            </div>
        </div>

        <div class="col-md-4 d-flex align-items-start justify-content-center pt-3">
            <?php
            Modal::begin([
                'title' => '<h4 class="m-0 fw-bold" style="color: #6b1d2f;">Últimos Comentarios</h4>',
                'toggleButton' => [
                    'label' => '<i class="fa fa-comments me-1"></i> Ver Comentarios',
                    'class' => 'btn btn-outline-dark btn-lg w-100 shadow-sm',
                    'style' => 'border-color: #6b1d2f; color: #6b1d2f;'
                ],
            ]);

            echo FacebookPlugin::widget([
                'type' => FacebookPlugin::COMMENT,
                'settings' => ['href' => 'http://www.yii2build.com', 'width' => '100%']
            ]);

            Modal::end();
            ?>
        </div>
    </div>

</div>