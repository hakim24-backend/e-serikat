<?php

namespace backend\controllers;

use Yii;
use common\models\ActivityDaily;
use common\models\ActivityDailyBudgetSecretariat;
use common\models\ActivityDailyBudgetChief;
use common\models\ActivityDailyBudgetDepart;
use common\models\ActivityDailyBudgetSection;
use common\models\ChiefBudget;
use common\models\SecretariatBudget;
use common\models\DepartmentBudget;
use common\models\SectionBudget;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * ActivityDailyReportController implements the CRUD actions for ActivityDaily model.
 */
class ActivityDailyReportController extends Controller
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
     * Lists all ActivityDaily models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => ActivityDaily::find()->where(['done'=>1]),
        ]);

        if (Yii::$app->request->get()) {
            $post = Yii::$app->request->get();

            //data sdm
            if ($post['jenis_sdm_source'] == 6) {
                if ($post['from_date'] && $post['to_date']) {
                $dateStart = $post['from_date'];
                $dateEnd = $post['to_date'];
                $dataProvider = new ActiveDataProvider([
                    'query' => ActivityDaily::find()->where(['done'=>1])->andWhere(['role'=>6])->andFilterWhere(['>=', 'date_start',$dateStart])->andFilterWhere(['<=', 'date_end',$dateEnd])
                ]);
                } else{
                    $dataProvider = new ActiveDataProvider([
                    'query' => ActivityDaily::find()->where(['done'=>1])->andWhere(['role'=>6])
                    ]);
                }
            } elseif ($post['jenis_sdm_source'] == 7) {
                $dateStart = $post['from_date'];
                $dateEnd = $post['to_date'];
                if ($post['from_date'] && $post['to_date']) {
                $dataProvider = new ActiveDataProvider([
                    'query' => ActivityDaily::find()->where(['done'=>1])->andWhere(['role'=>7])->andFilterWhere(['>=', 'date_start',$dateStart])->andFilterWhere(['<=', 'date_end',$dateEnd])
                ]);
                } else {
                    $dataProvider = new ActiveDataProvider([
                    'query' => ActivityDaily::find()->where(['done'=>1])->andWhere(['role'=>7])
                    ]);
                }
            } elseif ($post['jenis_sdm_source'] == 8) {
                $dateStart = $post['from_date'];
                $dateEnd = $post['to_date'];
                if ($post['from_date'] && $post['to_date']) {
                $dataProvider = new ActiveDataProvider([
                    'query' => ActivityDaily::find()->where(['done'=>1])->andWhere(['role'=>8])->andFilterWhere(['>=', 'date_start',$dateStart])->andFilterWhere(['<=', 'date_end',$dateEnd])
                ]);
                } else {
                    $dataProvider = new ActiveDataProvider([
                    'query' => ActivityDaily::find()->where(['done'=>1])->andWhere(['role'=>8])
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
     * Displays a single ActivityDaily model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {

      $role = Yii::$app->user->identity->role;

      // retrieve existing Deposit data
      $model = ActivityDaily::find()->where(['id' => $id])->one();

      if ($model->role == 4) {
            $model = ActivityDaily::find()->where(['id'=>$id])->one();
            $budget = ActivityDailyBudgetSecretariat::find()->where(['activity_id'=>$model])->one();
            $awal = ActivityDailyBudgetSecretariat::find()->where(['secretariat_budget_id'=>$budget])->one();
            $baru = SecretariatBudget::find()->where(['id'=>$awal])->one();
            $range = $model->date_start . ' to ' . $model->date_end;
            $range_start = $model->date_start;
            $range_end = $model->date_end;
            $oldDP = $budget->budget_value_dp;
            $oldBudget = $baru->secretariat_budget_value;

      } else if ($model->role == 6) {
            $budget = ActivityDailyBudgetChief::find()->where(['activity_id' => $model->id])->one();
            $awal = ActivityDailyBudgetChief::find()->where(['chief_budget_id' => $budget])->one();
            $baru = ChiefBudget::find()->where(['id' => $awal->chief_budget_id])->one();
            $range = $model->date_start . ' to ' . $model->date_end;
            $range_start = $model->date_start;
            $range_end = $model->date_end;

      } elseif ($model->role == 7) {
            $budget = ActivityDailyBudgetDepart::find()->where(['activity_id' => $model->id])->one();
            $awal = ActivityDailyBudgetDepart::find()->where(['department_budget_id' => $budget])->one();
            $baru = DepartmentBudget::find()->where(['id' => $awal->department_budget_id])->one();
            $range = $model->date_start . ' to ' . $model->date_end;
            $range_start = $model->date_start;
            $range_end = $model->date_end;
            $oldDP = $budget->budget_value_dp;
            $oldBudget = $baru->department_budget_value;

      } elseif ($model->role == 8) {
            $model = ActivityDaily::find()->where(['id'=>$id])->one();
            $budget = ActivityDailyBudgetSection::find()->where(['activity_id'=>$model])->one();
            $awal = ActivityDailyBudgetSection::find()->where(['section_budget_id'=>$budget])->one();
            $baru = SectionBudget::find()->where(['id'=>$awal])->one();
            $range = $model->date_start . ' to ' . $model->date_end;
            $range_start = $model->date_start;
            $range_end = $model->date_end;
            $oldDP = $budget->budget_value_dp;
            $oldBudget = $baru->section_budget_value;
      } 


        return $this->render('view', [
            'model' => $model,
            'budget' => $budget,
            'baru' => $baru,
            'range' => $range,
            'range_start' => $range_start,
            'range_end' => $range_end,
        ]);
    }

    /**
     * Creates a new ActivityDaily model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ActivityDaily();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing ActivityDaily model.
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
     * Deletes an existing ActivityDaily model.
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
     * Finds the ActivityDaily model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ActivityDaily the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ActivityDaily::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
