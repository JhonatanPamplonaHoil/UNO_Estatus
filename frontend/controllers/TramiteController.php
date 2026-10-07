<?php

namespace frontend\controllers;

use Yii;
use common\models\TramiteTitulacion;
use frontend\models\search\TramiteTitulacionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * TramiteController gestiona las acciones de consulta y registro para el egresado.
 */
class TramiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'], // Obligatorio haber iniciado sesión
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lista únicamente los trámites correspondientes al egresado autenticado.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new TramiteTitulacionSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        // Forzar la consulta a nivel de ActiveQuery para garantizar aislamiento total
        $dataProvider->query->andWhere(['tramite_titulacion.user_id' => Yii::$app->user->id]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Muestra el detalle del trámite validando que pertenezca al usuario en sesión.
     *
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Registro de una nueva solicitud de trámite.
     *
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new TramiteTitulacion();

        // Valores asignados por el sistema (no manipulables desde el formulario)
        $model->user_id = Yii::$app->user->id;
        $model->etapa_actual_id = 1; // Etapa 1: Recepción y Revisión de Documentos
        $model->folio = 'FOL-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -4));

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                Yii::$app->session->setFlash('success', 'Tu solicitud de trámite de titulación ha sido registrada exitosamente.');
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * El egresado no puede eliminar trámites institucionales directamente.
     *
     * @throws ForbiddenHttpException
     */
    public function actionDelete($id)
    {
        throw new ForbiddenHttpException('No está permitido eliminar expedientes de titulación.');
    }

    /**
     * Localiza el modelo asegurando la pertenencia al usuario actual.
     *
     * @param int $id ID
     * @return TramiteTitulacion
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (($model = TramiteTitulacion::findOne(['id' => $id, 'user_id' => Yii::$app->user->id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('El trámite solicitado no existe o no tiene permiso para consultarlo.');
    }
}