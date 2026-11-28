<?php

namespace backend\controllers;

use Yii;
use common\models\CitaEntrega;
use common\models\PermisosHelpers;
use backend\models\search\CitaEntregaSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * CitaEntregaController implements the CRUD actions for CitaEntrega model.
 */
class CitaEntregaController extends Controller
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
                    ],
                ],
            ]
        );
    }

    public function actionIndex()
    {
        $searchModel = new CitaEntregaSearch();
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
        $model = new CitaEntrega();

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

    /**
     * Cancela (elimina) la cita y envía correo de aviso al alumno.
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $emailAlumno = $model->tramite && $model->tramite->user ? $model->tramite->user->email : null;
        $nombreAlumno = $model->tramite && $model->tramite->user ? $model->tramite->user->username : 'Egresado';
        $fechaFormateada = Yii::$app->formatter->asDate($model->fecha, 'php:d/m/Y');

        if ($model->delete()) {
            $correoEnviado = false;

            if (!empty($emailAlumno)) {
                try {
                    $remitente = Yii::$app->params['senderEmail'] ?? Yii::$app->params['supportEmail'] ?? 'no-reply@uno.edu.mx';
                    $correoEnviado = Yii::$app->mailer->compose()
                        ->setFrom([$remitente => 'UNO Estatus - Servicios Escolares'])
                        ->setTo($emailAlumno)
                        ->setSubject('Aviso de cancelación de cita - UNO')
                        ->setTextBody(
                            "Hola {$nombreAlumno},\n\n" .
                            "La cita para el día {$fechaFormateada} ha sido cancelada, por favor seleccione otro día en el portal UNO Estatus.\n\n" .
                            "Atentamente,\nServicios Escolares - Universidad de Oriente"
                        )
                        ->send();
                } catch (\Throwable $e) {
                    Yii::error('Error al enviar correo de cancelación individual: ' . $e->getMessage(), __METHOD__);
                }
            }

            if ($correoEnviado) {
                Yii::$app->session->setFlash('success', 'La cita fue cancelada y se envió el correo de notificación al alumno.');
            } else {
                Yii::$app->session->setFlash('warning', 'La cita fue cancelada, pero no se pudo enviar el correo de notificación.');
            }
        }

        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = CitaEntrega::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}