<?php

declare(strict_types=1);

/** @var yii\web\View $this */
use yii\bootstrap5\Modal;
use kartik\social\FacebookPlugin;
use yii\bootstrap5\Collapse;
use yii\bootstrap5\Alert;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\bootstrap5\Accordion;

$this->title = 'Yii 2 Build';
$this->params['meta_description'] = 'A high-performance PHP framework best for developing web applications. Fast, secure, and professional.';
$this->params['meta_keywords'] = 'yii, yii2, php, framework, web application, high-performance';
?>
<div class="site-index">

    <div class="jumbotron">

        <?php if (Yii::$app->user->isGuest) {
            echo Html::a('Comience Hoy', ['site/signup'],
                ['class' => 'btn btn-lg btn-success']);
        } ?>

        <h1>Yii 2 Build <i class="fa fa-plug"></i></h1>

        <p class="lead">Use esta plantilla de Yii 2 para comenzar Proyectos.</p>

        <br/>

        <?php  echo FacebookPlugin::widget([
            'type'=>FacebookPlugin::LIKE,
            'settings' => []
        ]); ?>

    </div>

    <?php
    echo Accordion::widget([
        'items' => [
            [
                'label' => 'Características Principales' ,
                'content' => FacebookPlugin::widget([
                    'type'=>FacebookPlugin::SHARE,
                    'settings' => ['href'=>'http://www.yii2build.com','width'=>'350']
                ]),
            ],
            [
                'label' => 'Recursos Principales',
                'content' => FacebookPlugin::widget([
                    'type'=>FacebookPlugin::SHARE,
                    'settings' => ['href'=>'http://www.yii2build.com','width'=>'350']
                ]),
            ],
        ]
    ]);

    Modal::begin([
        'title' => '<h2>Últimos Comentarios</h2>',
        'toggleButton' => ['label' => 'comentarios'],
    ]);

    echo FacebookPlugin::widget([
        'type'=>FacebookPlugin::COMMENT,
        'settings' => ['href'=>'http://www.yii2build.com','width'=>'350']
    ]);

    Modal::end();
    ?>

    <br/>
    <br/>

    <?php
    echo Alert::widget([
        'options' => [
            'class' => 'alert-info',
        ],
        'body' => 'Lance su proyecto como un cohete...',
    ]);
    ?>

    <div class="body-content">
        <div class="row">
            <div class="col-lg-4">
                <h2>Gratis</h2>
                <p>
                    <?php
                    if (!Yii::$app->user->isGuest) {
                        echo Yii::$app->user->identity->username . ' está haciendo cosas geniales. ';
                    }
                    ?>
                    Partiendo de esta plantilla de código abierto y gratuita de Yii 2 ahorrará mucho tiempo.
                    Puede entregar proyectos al cliente rápidamente, con mucho de código ya disponible, para
                    que pueda concentrarse en los asuntos complejos.
                </p>
                <p>
                    <a class="btn btn-default" href="http://www.yiiframework.com/doc-2.0/guide-index.html">Documentación de Yii &raquo;</a>
                </p>
                <?php
                echo FacebookPlugin::widget([
                    'type'=>FacebookPlugin::LIKE,
                    'settings' => []
                ]);
                ?>
            </div>
            <div class="col-lg-4">
                <h2>Ventajas</h2>
                <p>
                    La facilidad de uso es una enorme ventaja.  Hemos simplificado el RBAC y le hemos
                    otorgado tipos de usuario Gratuito/Pago de manera predeterminada.  Los plugins sociales
                    son tan sencillos y rápidos de instalar, ¡que los amará!
                </p>
                <p>
                    <a class="btn btn-default" href="http://www.yiiframework.com/forum/">Foro de Yii &raquo;</a>
                </p>
                <?php
                echo FacebookPlugin::widget([
                    'type'=>FacebookPlugin::COMMENT,
                    'settings' => ['href'=>'http://www.yii2build.com','width'=>'350']
                ]);
                ?>
            </div>
            <div class="col-lg-4">
                <h2>¡Codifique Rápidamente, Codifique Correctamente!</h2>
                <p>
                    Libere el poder del asombroso framework Yii 2 con esta plantilla mejorada.
                    Con base en la plantilla avanzada de Yii 2, obtiene una implementación de frontend y
                    backend completa que presenta una IU rica para la administración del backend.
                </p>
                <p>
                    <a class="btn btn-default" href="http://www.yiiframework.com/extensions/">Extensiones de Yii &raquo;</a>
                </p>
            </div>
        </div>
    </div>
</div>