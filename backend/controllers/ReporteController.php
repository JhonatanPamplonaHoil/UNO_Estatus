<?php

declare(strict_types=1);

namespace backend\controllers;

use common\models\PermisosHelpers;
use Yii;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class ReporteController extends Controller
{
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => static function (): bool {
                            return PermisosHelpers::requerirMinimoRol('Admin');
                        },
                    ],
                ],
            ],
        ];
    }

    /**
     * Pantalla principal con botones verticales para cada reporte.
     */
    public function actionIndex(): string
    {
        $etapas = (new Query())
            ->select(['id', 'secuencia', 'nombre'])
            ->from('etapa_tramite')
            ->orderBy(['secuencia' => SORT_ASC])
            ->all();

        return $this->render('index', [
            'etapas' => $etapas,
        ]);
    }

    /**
     * Gráficas de barras y pastel de alumnos por estado de trámite (solo rol_id = 1).
     */
    public function actionGraficasEstados(): string
    {
        $datos = (new Query())
            ->select([
                'e.id',
                'e.nombre',
                'COUNT(u.id) AS total',
            ])
            ->from(['e' => 'etapa_tramite'])
            ->leftJoin(['t' => 'tramite_titulacion'], 't.etapa_actual_id = e.id')
            ->leftJoin(['u' => 'user'], 'u.id = t.user_id AND u.rol_id = 1')
            ->groupBy(['e.id', 'e.nombre', 'e.secuencia'])
            ->orderBy(['e.secuencia' => SORT_ASC])
            ->all();

        $totalGeneral = array_sum(array_map(static fn (array $row): int => (int) $row['total'], $datos));

        return $this->render('graficas-estados', [
            'datos' => $datos,
            'totalGeneral' => $totalGeneral,
        ]);
    }

    /**
     * Lista de alumnos (solo rol_id = 1) en una etapa específica.
     */
    public function actionListaAlumnos(int $etapa_id): string
    {
        $etapa = (new Query())
            ->select(['id', 'nombre'])
            ->from('etapa_tramite')
            ->where(['id' => $etapa_id])
            ->one();

        if (!$etapa) {
            throw new NotFoundHttpException('La etapa solicitada no existe.');
        }

        $alumnos = $this->obtenerAlumnosPorEtapa($etapa_id);

        return $this->render('lista-alumnos', [
            'etapa' => $etapa,
            'alumnos' => $alumnos,
        ]);
    }

    /**
     * Exporta la lista de alumnos de una etapa a Excel (CSV UTF-8).
     */
    public function actionExportarExcel(int $etapa_id): Response
    {
        $etapa = (new Query())
            ->select(['id', 'nombre'])
            ->from('etapa_tramite')
            ->where(['id' => $etapa_id])
            ->one();

        if (!$etapa) {
            throw new NotFoundHttpException('La etapa solicitada no existe.');
        }

        $alumnos = $this->obtenerAlumnosPorEtapa($etapa_id);

        $handle = fopen('php://temp', 'r+');
        // BOM UTF-8 para que Excel muestre correctamente acentos y eñes
        fwrite($handle, "\xEF\xBB\xBF");

        fputcsv($handle, ['Nombre completo', 'Matrícula', 'Teléfono', 'Correo electrónico']);

        foreach ($alumnos as $a) {
            $nombre = trim(
                ($a['perfil_nombre'] ?: $a['username']) . ' ' .
                ($a['apellido_paterno'] ?? '') . ' ' .
                ($a['apellido_materno'] ?? '')
            );

            fputcsv($handle, [
                $nombre,
                $a['matricula'],
                $a['telefono'] ?: 'No registrado',
                $a['email'],
            ]);
        }

        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);

        $slug = preg_replace('/[^a-zA-Z0-9_-]/', '_', (string) $etapa['nombre']);
        $filename = "Alumnos_{$slug}_" . date('Ymd') . '.csv';

        return Yii::$app->response->sendContentAsFile($csv, $filename, [
            'mimeType' => 'text/csv; charset=UTF-8',
        ]);
    }

    /**
     * Gráfica de barras de las fallas reportadas en el sistema.
     */
    public function actionGraficasFallas(): string
    {
        $porCategoria = (new Query())
            ->select(['categoria', 'COUNT(id) AS total'])
            ->from('reporte_falla')
            ->groupBy(['categoria'])
            ->orderBy(['total' => SORT_DESC])
            ->all();

        $porEstado = (new Query())
            ->select(['estado', 'COUNT(id) AS total'])
            ->from('reporte_falla')
            ->groupBy(['estado'])
            ->all();

        $totalFallas = array_sum(array_map(static fn (array $row): int => (int) $row['total'], $porCategoria));

        return $this->render('graficas-fallas', [
            'porCategoria' => $porCategoria,
            'porEstado' => $porEstado,
            'totalFallas' => $totalFallas,
        ]);
    }

    /**
     * Consulta reutilizable para alumnos (rol_id = 1) en una etapa.
     */
    private function obtenerAlumnosPorEtapa(int $etapaId): array
    {
        return (new Query())
            ->select([
                'u.username',
                'u.apellido_paterno',
                'u.apellido_materno',
                'u.telefono',
                'u.email',
                't.matricula',
                'p.nombre AS perfil_nombre',
            ])
            ->from(['t' => 'tramite_titulacion'])
            ->innerJoin(['u' => 'user'], 'u.id = t.user_id')
            ->leftJoin(['p' => 'perfil'], 'p.user_id = u.id')
            ->where([
                't.etapa_actual_id' => $etapaId,
                'u.rol_id' => 1,
            ])
            ->orderBy(['u.apellido_paterno' => SORT_ASC, 'u.username' => SORT_ASC])
            ->all();
    }
}