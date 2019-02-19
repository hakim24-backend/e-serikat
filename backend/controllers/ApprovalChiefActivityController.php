<?php

namespace backend\controllers;

use Yii;
use common\models\Activity;
use common\models\ActivityReject;
use common\models\ActivitySection;
use common\models\ActivitySectionMember;
use common\models\ActivityResponsibility;
use common\models\ActivityBudgetDepartment;
use common\models\DepartmentBudget;
use common\models\ActivityMainMember;
use common\models\ActivityBudgetChief;
use common\models\ChiefBudget;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * ApprovalChiefActivityController implements the CRUD actions for Activity model.
 */
class ApprovalChiefActivityController extends Controller
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
                        'actions' => ['logout', 'index','view','apply','update-apply','reject'],
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
            'query' => Activity::find()->where(['finance_status'=> 1])->andWhere(['department_status'=> 1])->andWhere(['chief_status'=> 0]),
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

    public function actionApply($id)
    {
        $model = Activity::find()->where(['id'=>$id])->one();
        $model->chief_status = 1;
        $model->save(false);
        Yii::$app->getSession()->setFlash('success', 'Kegiatan Rutin Berhasil Disetujui');
        return $this->redirect(Yii::$app->request->referrer);
        return $this->render([
            'model' => $model,
        ]);
    }

    public function actionReject($id)
    {
        $model = new ActivityReject();
        $reject = Activity::find()->where(['id'=>$id])->one();
        // var_dump($reject->role);die;
        if ($model->load(Yii::$app->request->post())) {
            $model->activity_id = $id;
            $save = $model->save(false);

            if ($reject->role == 7) {
                $modelRutin = Activity::find()->where(['id'=>$id])->one();
                $budget = ActivityBudgetDepartment::find()->where(['activity_id'=>$modelRutin])->one();
                $awal = ActivityBudgetDepartment::find()->where(['Department_budget_id'=>$budget])->one();
                $baru = DepartmentBudget::find()->where(['id'=>$awal])->one();

                $modelRutin->chief_status=2;
                $modelRutin->save(false);

                $baru->department_budget_value=$baru->department_budget_value+$budget->budget_value_dp;
                $baru->save();
            } else if ($reject->role == 8) {
                $modelSeksi = Activity::find()->where(['id'=>$id])->one();
                $budget = ActivityBudgetSection::find()->where(['activity_id'=>$modelSeksi])->one();
                $awal = ActivityBudgetSection::find()->where(['section_budget_id'=>$budget])->one();
                $baru = SectionBudget::find()->where(['id'=>$awal])->one();

                $modelSeksi->chief_status=2;
                $modelSeksi->save(false);

                $baru->section_budget_value=$baru->section_budget_value+$budget->budget_value_dp;
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
        if (($model = Activity::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
