<?php

namespace backend\controllers;

use Yii;
use common\models\CitaEntrega;
use common\models\DisponibilidadCita;
use common\models\PermisosHelpers;
use backend\models\search\DisponibilidadCitaSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * DisponibilidadCitaController implements the CRUD actions for DisponibilidadCita model.
 */
class DisponibilidadCitaController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'access' => [
                    'class' => AccessControl::class,
                    'rules' => [
                        [
                            'allow' => true,
                            'roles' => ['@'],
                            'matchCallback' => function ($rule, $action) {
                                return PermisosHelpers::requerirMinimoRol('Admin');
                            },
                        ],
                    ],
                ],
                'verbs' => [
                    'class' => VerbFilter::class,
                    'actions' => [
                        'delete' => ['POST'],
                        'crear-ajax' => ['POST'],
                        'actualizar-ajax' => ['POST'],
                        'eliminar-ajax' => ['POST'],
                    ],
                ],
            ]
        );
    }

    public function actionIndex()
    {
        $searchModel = new DisponibilidadCitaSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionCreate()
    {
        $model = new DisponibilidadCita();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $fecha = $model->fecha;

        if ($model->delete()) {
            $notificados = $this->cancelarCitasYNotificar($fecha);
            Yii::$app->session->setFlash(
                'success',
                "Disponibilidad eliminada. Se cancelaron y notificaron {$notificados} cita(s) agendada(s) para ese día."
            );
        }

        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = DisponibilidadCita::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    // =========================================================================
    // FUNCIONES PARA EL CALENDARIO (AJAX)
    // =========================================================================

    public function actionEventos()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $eventos = [];
        $disponibilidades = DisponibilidadCita::find()->all();

        foreach ($disponibilidades as $disp) {
            $eventos[] = [
                'id' => $disp->id,
                'title' => "Cupo: " . $disp->cupo_maximo,
                'start' => $disp->fecha . 'T' . $disp->hora_inicio,
                'end' => $disp->fecha . 'T' . $disp->hora_fin,
                'color' => '#198754',
                'extendedProps' => [
                    'hora_inicio' => $disp->hora_inicio,
                    'hora_fin' => $disp->hora_fin,
                    'cupo_maximo' => $disp->cupo_maximo,
                ],
            ];
        }
        return $eventos;
    }

    public function actionCrearAjax()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $model = new DisponibilidadCita();

        if (Yii::$app->request->isPost) {
            $model->fecha = Yii::$app->request->post('fecha');
            $model->hora_inicio = Yii::$app->request->post('hora_inicio');
            $model->hora_fin = Yii::$app->request->post('hora_fin');
            $model->cupo_maximo = Yii::$app->request->post('cupo_maximo');
            $model->created_at = date('Y-m-d H:i:s');
            $model->updated_at = date('Y-m-d H:i:s');

            if ($model->save()) {
                return ['success' => true];
            }
        }
        return ['success' => false, 'message' => 'Error al guardar.'];
    }

    public function actionActualizarAjax()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $id = Yii::$app->request->post('id');
        $model = DisponibilidadCita::findOne($id);

        if ($model && Yii::$app->request->isPost) {
            $model->hora_inicio = Yii::$app->request->post('hora_inicio');
            $model->hora_fin = Yii::$app->request->post('hora_fin');
            $model->cupo_maximo = Yii::$app->request->post('cupo_maximo');
            $model->updated_at = date('Y-m-d H:i:s');

            if ($model->save()) {
                return ['success' => true];
            }
        }
        return ['success' => false];
    }

    /**
     * Elimina una disponibilidad vía AJAX, cancela las citas de ese día y notifica por correo a los alumnos.
     */
    public function actionEliminarAjax()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $id = Yii::$app->request->post('id');
        $model = DisponibilidadCita::findOne($id);

        if ($model) {
            $fecha = $model->fecha;

            if ($model->delete()) {
                $notificados = $this->cancelarCitasYNotificar($fecha);
                return [
                    'success' => true,
                    'notificados' => $notificados,
                    'message' => "Las citas de ese día fueron canceladas. Alumnos notificados: {$notificados}.",
                ];
            }
        }

        return ['success' => false, 'message' => 'No se pudo cancelar la disponibilidad.'];
    }

    /**
     * Busca todas las citas agendadas en una fecha, las elimina y envía correo a cada alumno.
     */
    private function cancelarCitasYNotificar(string $fecha): int
    {
        $citas = CitaEntrega::find()
            ->with('tramite.user')
            ->where(['fecha' => $fecha])
            ->all();

        $fechaFormateada = Yii::$app->formatter->asDate($fecha, 'php:d/m/Y');
        $remitente = Yii::$app->params['senderEmail'] ?? Yii::$app->params['supportEmail'] ?? 'no-reply@uno.edu.mx';
        $enviados = 0;

        foreach ($citas as $cita) {
            $emailAlumno = $cita->tramite && $cita->tramite->user ? $cita->tramite->user->email : null;
            $nombreAlumno = $cita->tramite && $cita->tramite->user ? $cita->tramite->user->username : 'Egresado';

            if ($cita->delete() && !empty($emailAlumno)) {
                try {
                    $ok = Yii::$app->mailer->compose()
                        ->setFrom([$remitente => 'UNO Estatus - Servicios Escolares'])
                        ->setTo($emailAlumno)
                        ->setSubject('Aviso de cancelación de citas - UNO')
                        ->setTextBody(
                            "Hola {$nombreAlumno},\n\n" .
                            "Las citas del día {$fechaFormateada} han sido canceladas, por favor seleccione otro día en el portal UNO Estatus.\n\n" .
                            "Atentamente,\nServicios Escolares - Universidad de Oriente"
                        )
                        ->send();

                    if ($ok) {
                        $enviados++;
                    }
                } catch (\Throwable $e) {
                    Yii::error("Error al enviar correo masivo a {$emailAlumno}: " . $e->getMessage(), __METHOD__);
                }
            }
        }

        return $enviados;
    }
}