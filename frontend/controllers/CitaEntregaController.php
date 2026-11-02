<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use common\models\DisponibilidadCita;
use common\models\CitaEntrega;
use common\models\TramiteTitulacion;

class CitaEntregaController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'], // Solo alumnos logueados
                    ],
                ],
            ],
        ];
    }

    /**
     * Muestra la interfaz del calendario al alumno.
     */
   public function actionAgendar($tramite_id = null)
    {
        // Si no se envía ID en la URL, busca el trámite activo del alumno
        if ($tramite_id === null) {
            $tramite = TramiteTitulacion::findOne(['user_id' => Yii::$app->user->id]);
        } else {
            $tramite = TramiteTitulacion::findOne(['id' => $tramite_id, 'user_id' => Yii::$app->user->id]);
        }
        
        if (!$tramite) {
            throw new NotFoundHttpException('Aun no es tiempo de que agendes cita, espera a que llegue tu título!!.');
        }

        $citaExistente = CitaEntrega::findOne(['tramite_id' => $tramite->id, 'estado' => 'Agendada']);

        return $this->render('agendar', [
            'tramite' => $tramite,
            'citaExistente' => $citaExistente,
        ]);
    }
    /**
     * Retorna la disponibilidad en formato JSON para FullCalendar.
     */
    public function actionEventos($start, $end)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $disponibilidades = DisponibilidadCita::find()
            ->where(['>=', 'fecha', date('Y-m-d', strtotime($start))])
            ->andWhere(['<=', 'fecha', date('Y-m-d', strtotime($end))])
            ->all();

        $eventos = [];

        foreach ($disponibilidades as $disp) {
            $lugares = $disp->getLugaresDisponibles();
            $esDisponible = $lugares > 0;

            $eventos[] = [
                'id' => $disp->id,
                'title' => $esDisponible ? "Disponible ({$lugares})" : "Lleno",
                'start' => $disp->fecha . 'T' . $disp->hora_inicio,
                'end' => $disp->fecha . 'T' . $disp->hora_fin,
                'color' => $esDisponible ? '#28a745' : '#dc3545',
                'extendedProps' => [
                    'disponible' => $esDisponible,
                    'fecha_formateada' => Yii::$app->formatter->asDate($disp->fecha, 'php:d/m/Y'),
                    'horario' => $disp->hora_inicio . ' a ' . $disp->hora_fin,
                ]
            ];
        }

        return $eventos;
    }

    /**
     * Recibe la petición POST (AJAX) para guardar la cita.
     */
    public function actionGuardarCita()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $dispId = Yii::$app->request->post('disponibilidad_id');
        $tramiteId = Yii::$app->request->post('tramite_id');

        $disponibilidad = DisponibilidadCita::findOne($dispId);
        $tramite = TramiteTitulacion::findOne(['id' => $tramiteId, 'user_id' => Yii::$app->user->id]);

        if (!$disponibilidad || !$tramite) {
            return ['success' => false, 'message' => 'Datos inválidos.'];
        }

        if ($disponibilidad->getLugaresDisponibles() <= 0) {
            return ['success' => false, 'message' => 'Lo sentimos, este día ya se ha llenado.'];
        }

        $cita = new CitaEntrega();
        $cita->tramite_id = $tramite->id;
        $cita->fecha = $disponibilidad->fecha;
        $cita->hora = $disponibilidad->hora_inicio;
        $cita->estado = 'Agendada';
        $cita->created_at = date('Y-m-d H:i:s');
        $cita->updated_at = date('Y-m-d H:i:s');

        if ($cita->save()) {
            return ['success' => true, 'message' => 'Tu cita ha sido agendada correctamente.'];
        }

        return ['success' => false, 'message' => 'Ocurrió un error al guardar la cita.'];
    }
}