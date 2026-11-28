<?php

declare(strict_types=1);

use yii\bootstrap5\Html;

/** @var yii\web\View $this */
/** @var array $etapa */
/** @var array $alumnos */

$this->title = 'Alumnos en estado: ' . $etapa['nombre'];
$this->params['breadcrumbs'][] = ['label' => 'Generación de Reportes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
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

<div class="reporte-lista-alumnos py-2">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2 no-print">
        <?= Html::a('← Regresar a Reportes', ['index'], ['class' => 'btn btn-outline-secondary btn-sm']) ?>
        <div class="d-flex gap-2">
            <?= Html::a('📥 Exportar a Excel', ['exportar-excel', 'etapa_id' => $etapa['id']], [
                'class' => 'btn btn-success btn-sm px-3',
            ]) ?>
            <button type="button" onclick="window.print()" class="btn text-white btn-sm px-3" style="background-color: #7d0d30;">
                🖨️ Imprimir / Guardar PDF
            </button>
        </div>
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
                <small class="text-muted">Fecha de emisión: <?= date('d/m/Y H:i') ?> | Total: <?= count($alumnos) ?> alumno(s)</small>
            </div>
        </div>

        <?php if (empty($alumnos)): ?>
            <div class="alert alert-light border text-center mb-0">
                No hay alumnos registrados actualmente en este estado.
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-sm align-middle mb-0">
                    <thead style="background-color: #7d0d30; color: #ffffff;">
                        <tr>
                            <th>#</th>
                            <th>Nombre y Apellidos</th>
                            <th>Matrícula</th>
                            <th>Teléfono</th>
                            <th>Correo electrónico</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($alumnos as $index => $a): ?>
                            <?php
                            $nombreCompleto = trim(
                                ($a['perfil_nombre'] ?: $a['username']) . ' ' .
                                ($a['apellido_paterno'] ?? '') . ' ' .
                                ($a['apellido_materno'] ?? '')
                            );
                            ?>
                            <tr>
                                <td><?= $index + 1 ?></td>
                                <td class="fw-semibold"><?= Html::encode($nombreCompleto) ?></td>
                                <td><?= Html::encode($a['matricula']) ?></td>
                                <td><?= Html::encode($a['telefono'] ?: 'No registrado') ?></td>
                                <td><?= Html::encode($a['email']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>