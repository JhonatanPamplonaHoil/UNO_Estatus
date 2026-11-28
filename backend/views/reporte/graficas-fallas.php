<?php

declare(strict_types=1);

use yii\bootstrap5\Html;

/** @var yii\web\View $this */
/** @var array $porCategoria */
/** @var array $porEstado */
/** @var int $totalFallas */

$this->title = 'Reporte de Fallas Presentadas en el Sistema';
$this->params['breadcrumbs'][] = ['label' => 'Generación de Reportes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$labelsCat = array_column($porCategoria, 'categoria');
$totalesCat = array_map(static fn (array $r): int => (int) $r['total'], $porCategoria);
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

<div class="reporte-graficas-fallas py-2">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2 no-print">
        <?= Html::a('← Regresar a Reportes', ['index'], ['class' => 'btn btn-outline-secondary btn-sm']) ?>
        <button type="button" onclick="window.print()" class="btn text-white btn-sm px-3" style="background-color: #7d0d30;">
            🖨️ Imprimir / Exportar PDF
        </button>
    </div>

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
                <small class="text-muted">Fecha de emisión: <?= date('d/m/Y H:i') ?> | Total de reportes: <?= $totalFallas ?></small>
            </div>
        </div>

        <div class="mb-4">
            <canvas id="graficaBarrasFallas" height="120"></canvas>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-sm align-middle mb-0">
                <thead style="background-color: #7d0d30; color: #ffffff;">
                    <tr>
                        <th>Categoría de falla</th>
                        <th class="text-center">Cantidad reportada</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($porCategoria as $fila): ?>
                        <tr>
                            <td><?= Html::encode($fila['categoria']) ?></td>
                            <td class="text-center fw-semibold"><?= (int) $fila['total'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
new Chart(document.getElementById('graficaBarrasFallas'), {
    type: 'bar',
    data: {
        labels: <?= json_encode($labelsCat, JSON_UNESCAPED_UNICODE) ?>,
        datasets: [{
            label: 'Reportes de falla',
            data: <?= json_encode($totalesCat) ?>,
            backgroundColor: '#7d0d30',
            borderRadius: 6
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
    }
});
</script>