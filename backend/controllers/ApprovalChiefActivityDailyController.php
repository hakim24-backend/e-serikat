<?php

namespace backend\controllers;

use Yii;
use common\models\ActivityDaily;
use common\models\ActivityDailyReject;
use common\models\ActivityDailyResponsibility;
use common\models\ActivityDailyBudgetChief;
use common\models\ChiefBudget;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * ApprovalChiefActivityDailyController implements the CRUD actions for Activity model.
 */
class ApprovalChiefActivityDailyController extends Controller
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
                        'actions' => ['logout','index','view','apply','update-apply','reject'],
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
            'query' => ActivityDaily::find()->where(['finance_status'=> 1])->andWhere(['department_status'=> 1])->andWhere(['chief_status'=> 0]),
        ]);

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
    public function actionApply($id)
    {
        $model = ActivityDaily::find()->where(['id'=>$id])->one();
        $model->chief_status = 1;
        $model->save(false);
        Yii::$app->getSession()->setFlash('success', 'Kegiatan Rutin Berhasil Disetujui');
        return $this->redirect(Yii::$app->request->referrer);
        return $this->render([
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
    public function actionReject($id)
    {
        $model = new ActivityDailyReject();
        $reject = ActivityDaily::find()->where(['id'=>$id])->one();

        if ($model->load(Yii::$app->request->post())) {
            $model->activity_id = $id;
            $model->save(false);

            $roleDepart =  ActivityDaily::find()->where(['role'=>7])->one();
            $roleSeksi =  ActivityDaily::find()->where(['role'=>8])->one();

            if ($roleDepart) {
                $modelChief = ActivityDaily::find()->where(['id'=>$id])->one();
                $budget = ActivityDailyBudgetChief::find()->where(['activity_id'=>$modelChief])->one();
                $awal = ActivityDailyBudgetChief::find()->where(['chief_budget_id'=>$budget])->one();
                $baru = ChiefBudget::find()->where(['id'=>$awal])->one();
                $approve = ActivityDailyResponsibility::find()->where(['activity_id'=>$modelChief])->one();
                $departBudget = ActivityDailyBudgetChief::find()->where(['activity_id'=>$modelChief])->one();

                $modelChief->chief_status=2;
                $modelChief->save(false);

                $baru->chief_budget_value=$baru->chief_budget_value+$budget->budget_value_dp;
                $baru->save();
            } elseif ($roleSeksi) {
                $modelSeksi = ActivityDaily::find()->where(['id'=>$id])->one();
                $budget = ActivityDailyBudgetSection::find()->where(['activity_id'=>$modelSeksi])->one();
                $awal = ActivityDailyBudgetSection::find()->where(['section_budget_id'=>$budget])->one();
                $baru = SectionBudget::find()->where(['id'=>$awal])->one();
                $approve = ActivityDailyResponsibility::find()->where(['activity_id'=>$modelSeksi])->one();
                $departBudget = ActivityDailyBudgetSection::find()->where(['activity_id'=>$modelSeksi])->one();

                $modelSeksi->chief_status=2;
                $modelSeksi->save(false);

                $baru->chief_budget_value=$baru->chief_budget_value+$budget->budget_value_dp;
                $baru->save();
            }

        Yii::$app->getSession()->setFlash('info', 'Kegiatan Berhasil Ditolak');
        return $this->redirect(['index']);

        }

        return $this->render('_form', [
            'model' => $model,
        ]);
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
        if (($model = ActivityDaily::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
