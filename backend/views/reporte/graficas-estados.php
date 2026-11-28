<?php

declare(strict_types=1);

use yii\bootstrap5\Html;

/** @var yii\web\View $this */
/** @var array $datos */
/** @var int $totalGeneral */

$this->title = 'Reporte Estadístico de Alumnos por Estado';
$this->params['breadcrumbs'][] = ['label' => 'Generación de Reportes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$labels = array_column($datos, 'nombre');
$totales = array_map(static fn (array $r): int => (int) $r['total'], $datos);
$porcentajes = array_map(
    static fn (int $t): float => $totalGeneral > 0 ? round(($t / $totalGeneral) * 100, 1) : 0.0,
    $totales
);
?>

<style>
.hoja-uno {
    background: #ffffff;
    border: 1px solid #e2d1d6;
    border-radius: 12px;
    padding: 2rem;
}
@media print {
    @page {
        margin: 0;
    }
    header, footer, nav, .breadcrumb, .no-print, .btn-regresar-global {
        display: none !important;
    }
    body, .container, main {
        margin: 0 !important;
        padding: 0 !important;
        max-width: 100% !important;
        background: #ffffff !important;
    }
    .hoja-uno {
        border: none !important;
        box-shadow: none !important;
        padding: 1.5cm !important;
    }
}
</style>

<div class="reporte-graficas-estados py-2">
    <!-- Barra de acciones (oculta al imprimir/PDF) -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2 no-print">
        <?= Html::a('← Regresar a Reportes', ['index'], ['class' => 'btn btn-outline-secondary btn-sm']) ?>
        <button type="button" onclick="window.print()" class="btn text-white btn-sm px-3" style="background-color: #7d0d30;">
            🖨️ Imprimir / Exportar PDF
        </button>
    </div>

    <!-- Hoja con formato institucional UNO -->
    <div class="hoja-uno shadow-sm">
        <div class="d-flex align-items-center justify-content-between border-bottom pb-3 mb-4">
            <div>
                <?= Html::img(Yii::getAlias('@web/images/UnoiM.png'), [
                    'alt' => 'UNO',
                    'style' => 'max-height: 55px;',
                ]) ?>
            </div>
            <div class="text-end">
                <h2 class="h5 fw-bold mb-0 text-uppercase" style="color: #7d0d30;">
                    <?= Html::encode($this->title) ?>
                </h2>
                <small class="text-muted">Fecha de emisión: <?= date('d/m/Y H:i') ?> | Total de alumnos: <?= $totalGeneral ?></small>
            </div>
        </div>

        <div class="row g-4 align-items-center mb-4">
            <div class="col-12 col-lg-7">
                <h6 class="fw-bold text-center mb-3">Cantidad de alumnos por estado (Barras)</h6>
                <canvas id="graficaBarrasEstados" height="160"></canvas>
            </div>
            <div class="col-12 col-lg-5">
                <h6 class="fw-bold text-center mb-3">Distribución porcentual (Pastel)</h6>
                <canvas id="graficaPastelEstados" height="200"></canvas>
            </div>
        </div>

        <!-- Tabla resumen -->
        <div class="table-responsive">
            <table class="table table-bordered table-sm align-middle mb-0">
                <thead style="background-color: #7d0d30; color: #ffffff;">
                    <tr>
                        <th>Estado del trámite</th>
                        <th class="text-center">Cantidad de alumnos</th>
                        <th class="text-center">Porcentaje</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($datos as $i => $fila): ?>
                        <tr>
                            <td><?= Html::encode($fila['nombre']) ?></td>
                            <td class="text-center fw-semibold"><?= (int) $fila['total'] ?></td>
                            <td class="text-center"><?= $porcentajes[$i] ?>%</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const labelsEstados = <?= json_encode($labels, JSON_UNESCAPED_UNICODE) ?>;
const totalesEstados = <?= json_encode($totales) ?>;
const porcentajesEstados = <?= json_encode($porcentajes) ?>;
const coloresUNO = ['#7d0d30', '#b51746', '#d97706', '#0d6efd', '#198754', '#6f42c1'];

new Chart(document.getElementById('graficaBarrasEstados'), {
    type: 'bar',
    data: {
        labels: labelsEstados,
        datasets: [{
            label: 'Alumnos',
            data: totalesEstados,
            backgroundColor: coloresUNO,
            borderRadius: 6
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
    }
});

new Chart(document.getElementById('graficaPastelEstados'), {
    type: 'pie',
    data: {
        labels: labelsEstados.map((l, i) => l + ' (' + porcentajesEstados[i] + '%)'),
        datasets: [{
            data: totalesEstados,
            backgroundColor: coloresUNO
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { position: 'bottom' } }
    }
});
</script>