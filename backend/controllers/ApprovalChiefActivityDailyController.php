<?php

namespace backend\controllers;

use Yii;
use common\models\ActivityDaily;
use common\models\ActivityDailyReject;
use common\models\ActivityDailyResponsibility;
use common\models\ActivityDailyBudgetChief;
use common\models\ActivityDailyBudgetDepart;
use common\models\ActivityDailyBudgetSection;
use common\models\ChiefBudget;
use common\models\DepartmentBudget;
use common\models\SectionBudget;
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
      $role = Yii::$app->user->identity->role;
      $id_chief = Yii::$app->user->identity->chief->id;

        $dataProvider = new ActiveDataProvider([
            'query' => ActivityDaily::find()
            ->where(['finance_status'=> 1])
            // ->andWhere(['role' => $role])
            ->andWhere(['chief_code_id'=>$id_chief])
            ->andWhere(['department_status'=> 1])
            ->andWhere(['chief_status'=> 0]),
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
      $role = Yii::$app->user->identity->role;
      $model = ActivityDaily::find()->where(['id' => $id])->one();

      if ($model->role == 6) {
          $budget = ActivityDailyBudgetChief::find()->where(['activity_id' => $model->id])->one();
          $baru = ChiefBudget::find()->where(['id' => $budget->chief_budget_id])->one();
          $range = $model->date_start . ' to ' . $model->date_end;
          $range_start = $model->date_start;
          $range_end = $model->date_end;
          $oldDP = $budget->budget_value_dp;
          $oldBudget = $baru->chief_budget_value;

      }else if ($model->role == 7) {
          $budget = ActivityDailyBudgetDepart::find()->where(['activity_id' => $model->id])->one();
          $baru = DepartmentBudget::find()->where(['id' => $budget->department_budget_id])->one();
          $range = $model->date_start . ' to ' . $model->date_end;
          $range_start = $model->date_start;
          $range_end = $model->date_end;
          $oldDP = $budget->budget_value_dp;
          $oldBudget = $baru->department_budget_value;

      }else if ($model->role == 8) {
          $budget = ActivityDailyBudgetSection::find()->where(['activity_id' => $model->id])->one();
          $baru = SectionBudget::find()->where(['id' => $budget->section_budget_id])->one();
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
     * Creates a new Activity model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionApply($id)
    {
        $model = ActivityDaily::find()->where(['id'=>$id])->one();
        $model->chief_status = 1;
        if($model->save(false)){
          Yii::$app->getSession()->setFlash('success', 'Kegiatan Rutin Berhasil Disetujui');
          return $this->redirect(Yii::$app->request->referrer);
        }
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

            if ($reject->role == 6) {
                $modelChief = ActivityDaily::find()->where(['id'=>$id])->one();
                $budget = ActivityDailyBudgetChief::find()->where(['activity_id'=>$modelChief->id])->one();
                $baru = ChiefBudget::find()->where(['id'=>$budget->chief_budget_id])->one();

                // var_dump($id);die;
                $approve = ActivityDailyResponsibility::find()->where(['activity_id'=>$modelChief->id])->one();
                $departBudget = ActivityDailyBudgetChief::find()->where(['activity_id'=>$modelChief->id])->one();

                $modelChief->chief_status=2;
                $modelChief->save(false);

                $baru->chief_budget_value=$baru->chief_budget_value+$budget->budget_value_sum;
                $baru->save();
            } else if ($reject->role == 7) {
                $modelDep = ActivityDaily::find()->where(['id'=>$id])->one();
                $budget = ActivityDailyBudgetDepart::find()->where(['activity_id'=>$modelDep->id])->one();
                $baru = DepartmentBudget::find()->where(['id'=>$budget->department_budget_id])->one();

                // var_dump($id);die;
                $approve = ActivityDailyResponsibility::find()->where(['activity_id'=>$modelDep->id])->one();
                $departBudget = ActivityDailyBudgetDepart::find()->where(['activity_id'=>$modelDep->id])->one();

                $modelDep->chief_status=2;
                $modelDep->save(false);

                $baru->department_budget_value=$baru->department_budget_value+$budget->budget_value_sum;
                $baru->save();
            } else if ($reject->role == 8) {
                $modelSeksi = ActivityDaily::find()->where(['id'=>$id])->one();
                $budget = ActivityDailyBudgetSection::find()->where(['activity_id'=>$modelSeksi->id])->one();
                $baru = SectionBudget::find()->where(['id'=>$budget->section_budget_id])->one();
                $approve = ActivityDailyResponsibility::find()->where(['activity_id'=>$modelSeksi->id])->one();
                $departBudget = ActivityDailyBudgetSection::find()->where(['activity_id'=>$modelSeksi->id])->one();

                $modelSeksi->chief_status=2;
                $modelSeksi->save(false);

                $baru->section_budget_value=$baru->section_budget_value+$budget->budget_value_sum;
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
