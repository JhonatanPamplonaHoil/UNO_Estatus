<?php

declare(strict_types=1);

namespace backend\controllers;

use common\models\PermisosHelpers;
use common\models\ReporteFalla;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class ReporteFallaController extends Controller
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
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    public function actionIndex(?string $estado = null): string
    {
        $query = ReporteFalla::find()->orderBy(['created_at' => SORT_DESC]);

        if ($estado !== null && in_array($estado, array_keys(ReporteFalla::getEstados()), true)) {
            $query->andWhere(['estado' => $estado]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 15],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'estadoFiltro' => $estado,
        ]);
    }

    public function actionView(int $id): string|Response
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'El estado y las notas del reporte se actualizaron correctamente.');
            return $this->refresh();
        }

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    public function actionDelete(int $id): Response
    {
        $this->findModel($id)->delete();
        Yii::$app->session->setFlash('success', 'Reporte eliminado correctamente.');

        return $this->redirect(['index']);
    }

    protected function findModel(int $id): ReporteFalla
    {
        $model = ReporteFalla::findOne($id);
        if ($model === null) {
            throw new NotFoundHttpException('El reporte solicitado no existe.');
        }

        return $model;
    }
}