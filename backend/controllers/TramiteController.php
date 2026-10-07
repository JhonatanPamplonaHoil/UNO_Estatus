<?php

namespace backend\controllers;

use Yii;
use common\models\TramiteTitulacion;
use backend\models\search\TramiteTitulacionSearch;
use common\models\AuditoriaTramite;
use common\models\EtapaTramite;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * TramiteController implementa el CRUD y la gestión administrativa de trámites.
 */
class TramiteController extends Controller
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
                            'roles' => ['@'], // Requiere usuario autenticado en backend
                        ],
                    ],
                ],
                'verbs' => [
                    'class' => VerbFilter::class,
                    'actions' => [
                        'delete' => ['POST'],
                        'cambiar-etapa' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lista todos los trámites registrados en la universidad.
     */
    public function actionIndex()
    {
        $searchModel = new TramiteTitulacionSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Muestra el detalle del trámite y su historial de auditoría.
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        
        // Obtenemos el historial de auditoría ordenado del más reciente al más antiguo
        $historialAuditoria = AuditoriaTramite::find()
            ->where(['tramite_id' => $model->id])
            ->orderBy(['created_at' => SORT_DESC])
            ->all();

        return $this->render('view', [
            'model' => $model,
            'historialAuditoria' => $historialAuditoria,
        ]);
    }

    /**
     * Avanza o actualiza la etapa del trámite y registra la auditoría inmutable.
     */
    public function actionCambiarEtapa($id)
    {
        $model = $this->findModel($id);
        $nuevaEtapaId = Yii::$app->request->post('nueva_etapa_id');
        $motivo = Yii::$app->request->post('motivo', 'Actualización de etapa por Servicios Escolares');

        if ($nuevaEtapaId && $nuevaEtapaId != $model->etapa_actual_id) {
            $etapaAnteriorId = $model->etapa_actual_id;

            // Transacción para garantizar que si falla la auditoría, no cambie el trámite
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $model->etapa_actual_id = (int)$nuevaEtapaId;
                if ($model->save(false)) {
                    // Registro en la bitácora inmutable
                    $auditoria = new AuditoriaTramite();
                    $auditoria->tramite_id = $model->id;
                    $auditoria->etapa_anterior_id = $etapaAnteriorId;
                    $auditoria->etapa_nueva_id = (int)$nuevaEtapaId;
                    $auditoria->usuario_responsable_id = Yii::$app->user->id;
                    $auditoria->motivo_cambio = $motivo;
                    $auditoria->ip_origen = Yii::$app->request->userIP ?? '127.0.0.1';
                    $auditoria->created_at = date('Y-m-d H:i:s');

                    if ($auditoria->save(false)) {
                        $transaction->commit();
                        Yii::$app->session->setFlash('success', 'La etapa del trámite se actualizó correctamente y quedó asentada en la bitácora.');
                    } else {
                        $transaction->rollBack();
                        Yii::$app->session->setFlash('error', 'No se pudo asentar el cambio en la bitácora de auditoría.');
                    }
                } else {
                    $transaction->rollBack();
                    Yii::$app->session->setFlash('error', 'Error al actualizar el trámite.');
                }
            } catch (\Exception $e) {
                $transaction->rollBack();
                Yii::$app->session->setFlash('error', 'Excepción: ' . $e->getMessage());
            }
        } else {
            Yii::$app->session->setFlash('warning', 'Debes seleccionar una etapa diferente a la actual.');
        }

        return $this->redirect(['view', 'id' => $model->id]);
    }

    /**
     * Modifica datos del trámite.
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
     * Elimina el trámite si no tiene restricciones.
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }

    /**
     * Busca el modelo TramiteTitulacion.
     */
    protected function findModel($id)
    {
        if (($model = TramiteTitulacion::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('El trámite solicitado no existe.');
    }
}