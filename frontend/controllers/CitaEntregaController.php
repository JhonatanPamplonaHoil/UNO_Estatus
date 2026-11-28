<?php

namespace frontend\controllers;

use Yii;
use yii\db\Query;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
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
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'guardar-cita' => ['POST'],
                    'cancelar-cita' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Muestra la interfaz del calendario al alumno solo si su etapa permite cita.
     */
    public function actionAgendar($tramite_id = null)
    {
        if ($tramite_id === null) {
            $tramite = TramiteTitulacion::findOne(['user_id' => Yii::$app->user->id]);
        } else {
            $tramite = TramiteTitulacion::findOne(['id' => $tramite_id, 'user_id' => Yii::$app->user->id]);
        }

        if (!$tramite) {
            Yii::$app->session->setFlash('warning', 'Aún no tienes un trámite de titulación registrado.');
            return $this->redirect(['tramite/index']);
        }

        if (!$this->puedeAgendarCita($tramite)) {
            Yii::$app->session->setFlash(
                'warning',
                'Aún no puedes agendar cita. Debes esperar a que tu estatus cambie a "Título Disponible en Ventanilla".'
            );
            return $this->redirect(['tramite/index']);
        }

        $citaExistente = CitaEntrega::findOne([
            'tramite_id' => $tramite->id,
            'estado' => 'Agendada',
        ]);

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
                ],
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

        if (!$this->puedeAgendarCita($tramite)) {
            return [
                'success' => false,
                'message' => 'Aún no puedes agendar cita. Tu título debe estar en estatus "Título Disponible en Ventanilla".',
            ];
        }

        $yaTieneCita = CitaEntrega::find()
            ->where(['tramite_id' => $tramite->id, 'estado' => 'Agendada'])
            ->exists();

        if ($yaTieneCita) {
            return ['success' => false, 'message' => 'Ya cuentas con una cita agendada.'];
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

    /**
     * Cancela (elimina) la cita agendada del alumno para que pueda elegir otra fecha.
     */
    public function actionCancelarCita($id)
    {
        $tramite = TramiteTitulacion::findOne(['user_id' => Yii::$app->user->id]);

        if (!$tramite) {
            Yii::$app->session->setFlash('error', 'No se encontró tu trámite.');
            return $this->redirect(['tramite/index']);
        }

        $cita = CitaEntrega::findOne([
            'id' => $id,
            'tramite_id' => $tramite->id,
            'estado' => 'Agendada',
        ]);

        if (!$cita) {
            Yii::$app->session->setFlash('warning', 'No se encontró una cita activa para cancelar.');
            return $this->redirect(['agendar']);
        }

        if ($cita->delete()) {
            Yii::$app->session->setFlash('success', 'Tu cita ha sido cancelada. Ahora puedes seleccionar una nueva fecha disponible.');
        } else {
            Yii::$app->session->setFlash('error', 'No se pudo cancelar la cita. Inténtalo de nuevo.');
        }

        return $this->redirect(['agendar']);
    }

    /**
     * Verifica en la base de datos si la etapa actual del trámite tiene permite_cita = 1.
     */
    private function puedeAgendarCita(TramiteTitulacion $tramite): bool
    {
        $permiteCita = (new Query())
            ->select('permite_cita')
            ->from('etapa_tramite')
            ->where(['id' => $tramite->etapa_actual_id])
            ->scalar();

        return (int) $permiteCita === 1;
    }
}