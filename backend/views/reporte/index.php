<?php

declare(strict_types=1);

use yii\bootstrap5\Html;

/** @var yii\web\View $this */
/** @var array $etapas */

$this->title = 'Generación de Reportes';
$this->params['breadcrumbs'][] = $this->title;
?>

<style>
.uno-report-btn {
    border: 1.5px solid #e2d1d6;
    border-left: 5px solid #7d0d30;
    background-color: #ffffff;
    color: #2b030f;
    font-weight: 600;
    text-align: left;
    padding: 1rem 1.25rem;
    border-radius: 0.75rem;
    transition: all 0.2s ease;
}
.uno-report-btn:hover {
    background-color: #7d0d30;
    border-color: #7d0d30;
    color: #ffffff;
    transform: translateX(4px);
}
</style>

<div class="reporte-index py-2">
    <div class="mb-4">
        <h1 class="h3 fw-bold mb-1" style="color: #7d0d30;"><?= Html::encode($this->title) ?></h1>
        <p class="text-muted small mb-0">Selecciona el tipo de reporte o listado que deseas consultar y exportar.</p>
    </div>

    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">

            <!-- Sección 1: Gráficas generales -->
            <div class="card border-0 shadow-sm rounded-3 mb-4">
                <div class="card-header bg-white fw-bold py-3">📊 Reportes Estadísticos y Gráficas</div>
                <div class="card-body d-grid gap-2">
                    <?= Html::a(
                        '📊 Gráficas de Alumnos por Estado de Trámite (Barras y Pastel)',
                        ['graficas-estados'],
                        ['class' => 'btn uno-report-btn']
                    ) ?>

                    <?= Html::a(
                        '🛠️ Gráfica de Barras de Fallas Presentadas en el Sistema',
                        ['graficas-fallas'],
                        ['class' => 'btn uno-report-btn']
                    ) ?>
                </div>
            </div>

            <!-- Sección 2: Listas de alumnos por cada estado -->
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white fw-bold py-3">📋 Listados de Alumnos por Estado</div>
                <div class="card-body d-grid gap-2">
                    <?php foreach ($etapas as $etapa): ?>
                        <?= Html::a(
                            '👤 Alumnos en estado: ' . Html::encode($etapa['nombre']),
                            ['lista-alumnos', 'etapa_id' => $etapa['id']],
                            ['class' => 'btn uno-report-btn']
                        ) ?>
                    <?php endforeach; ?>
                </div>
            </div>

        </div>
    </div>
</div>