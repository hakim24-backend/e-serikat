<?php

namespace backend\controllers;

use Yii;
use common\models\Activity;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * ActivityReportController implements the CRUD actions for Activity model.
 */
class ActivityReportController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout','index','view','create','update','delete'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Activity models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Activity::find()->where(['done'=>1])
        ]);

        if (Yii::$app->request->get()) {
            $post = Yii::$app->request->get();

            //data sdm
            if ($post['jenis_sdm_source'] == 6) {
                if ($post['from_date'] && $post['to_date']) {
                $dateStart = $post['from_date'];
                $dateEnd = $post['to_date'];
                $dataProvider = new ActiveDataProvider([
                    'query' => Activity::find()->where(['done'=>1])->andWhere(['role'=>6])->andFilterWhere(['>=', 'date_start',$dateStart])->andFilterWhere(['<=', 'date_end',$dateEnd])
                ]);
                } else{
                    $dataProvider = new ActiveDataProvider([
                    'query' => Activity::find()->where(['done'=>1])->andWhere(['role'=>6])
                    ]);
                }
            } elseif ($post['jenis_sdm_source'] == 7) {
                $dateStart = $post['from_date'];
                $dateEnd = $post['to_date'];
                if ($post['from_date'] && $post['to_date']) {
                $dataProvider = new ActiveDataProvider([
                    'query' => Activity::find()->where(['done'=>1])->andWhere(['role'=>7])->andFilterWhere(['>=', 'date_start',$dateStart])->andFilterWhere(['<=', 'date_end',$dateEnd])
                ]);
                } else {
                    $dataProvider = new ActiveDataProvider([
                    'query' => Activity::find()->where(['done'=>1])->andWhere(['role'=>7])
                    ]);
                }
            } elseif ($post['jenis_sdm_source'] == 8) {
                $dateStart = $post['from_date'];
                $dateEnd = $post['to_date'];
                if ($post['from_date'] && $post['to_date']) {
                $dataProvider = new ActiveDataProvider([
                    'query' => Activity::find()->where(['done'=>1])->andWhere(['role'=>8])->andFilterWhere(['>=', 'date_start',$dateStart])->andFilterWhere(['<=', 'date_end',$dateEnd])
                ]);
                } else {
                    $dataProvider = new ActiveDataProvider([
                    'query' => Activity::find()->where(['done'=>1])->andWhere(['role'=>8])
                    ]);
                }
            }

            // //range tanggal
            // if ($post['from_date'] && $post['to_date']) {
            //     $dateStart = $post['from_date'];
            //     $dateEnd = $post['to_date'];
            //     $dataProvider = new ActiveDataProvider([
            //         'query' => Activity::find()->where(['done'=>1])->andFilterWhere(['>=', 'date_start',$dateStart])->andFilterWhere(['<=', 'date_end',$dateEnd])
            //     ]);
            // }
        }

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Activity model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Activity model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Activity();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Activity model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Activity model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Activity model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Activity the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Activity::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
