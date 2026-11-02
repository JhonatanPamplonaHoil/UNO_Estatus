<?php

namespace backend\controllers;

use Yii; // <-- IMPORTANTE: Agregado para usar Yii::$app en AJAX
use common\models\DisponibilidadCita;
use backend\models\search\DisponibilidadCitaSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

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
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all DisponibilidadCita models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new DisponibilidadCitaSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single DisponibilidadCita model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new DisponibilidadCita model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
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

    /**
     * Updates an existing DisponibilidadCita model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
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
     * Deletes an existing DisponibilidadCita model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the DisponibilidadCita model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return DisponibilidadCita the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DisponibilidadCita::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    // =========================================================================
    // NUEVAS FUNCIONES PARA EL CALENDARIO (AJAX)
    // =========================================================================

    /**
     * Retorna los eventos en JSON para FullCalendar
     */
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
                'color' => '#198754', // Verde Bootstrap
                'extendedProps' => [
                    'hora_inicio' => $disp->hora_inicio,
                    'hora_fin' => $disp->hora_fin,
                    'cupo_maximo' => $disp->cupo_maximo,
                ]
            ];
        }
        return $eventos;
    }

    /**
     * Crea una nueva disponibilidad vía AJAX
     */
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

    /**
     * Actualiza una disponibilidad existente vía AJAX
     */
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
     * Elimina una disponibilidad vía AJAX
     */
    public function actionEliminarAjax()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $id = Yii::$app->request->post('id');
        $model = DisponibilidadCita::findOne($id);
        
        if ($model && $model->delete()) {
            return ['success' => true];
        }
        return ['success' => false];
    }
}
