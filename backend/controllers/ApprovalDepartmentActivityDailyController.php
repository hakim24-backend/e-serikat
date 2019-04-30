<?php

namespace backend\controllers;

use Yii;
use common\models\ActivityDaily;
use common\models\ActivityDailyReject;
use common\models\ActivityDailyResponsibility;
use common\models\ActivityDailyBudgetSection;
use common\models\SectionBudget;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * ApprovalDepartmentActivityDailyController implements the CRUD actions for ActivityDaily model.
 */
class ApprovalDepartmentActivityDailyController extends Controller
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
                        'actions' => ['index','view','apply','update-apply','reject'],
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

        $role = Yii::$app->user->identity->role;
        $atasan = Yii::$app->user->identity->department->id;

        $dataProvider = new ActiveDataProvider([
            'query' => ActivityDaily::find()
                        ->joinWith('activityDailyBudgetSections')
                        ->joinWith('activityDailyBudgetSections.sectionBudget')
                        ->joinWith('activityDailyBudgetSections.sectionBudget.section')
                        ->where(['role'=>8])
                        ->andWhere(['finance_status'=> 1])->andWhere(['chief_status'=>0])->andWhere(['department_status'=>0])
                        ->andWhere(['section.id_depart'=>$atasan])
                        ->andWhere(['activity_daily.done'=>0]),

        ]);

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
      $model = ActivityDaily::find()->where(['id' => $id])->one();

      if ($model->role == 8) {
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
     * Creates a new ActivityDaily model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionApply($id)
    {
        $model = ActivityDaily::find()->where(['id'=>$id])->one();
        $model->department_status = 1;
        $model->save(false);
        Yii::$app->getSession()->setFlash('success', 'Kegiatan Rutin Berhasil Disetujui');
        return $this->redirect(Yii::$app->request->referrer);
        return $this->render([
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
    public function actionReject($id)
    {
        $model = new ActivityDailyReject();
        $reject = ActivityDaily::find()->where(['id'=>$id])->one();

        if ($model->load(Yii::$app->request->post())) {
            $model->activity_id = $id;
            $model->save(false);

            $modelSection = ActivityDaily::find()->where(['id'=>$id])->one();
            $budget = ActivityDailyBudgetSection::find()->where(['activity_id'=>$modelSection->id])->one();
            $baru = SectionBudget::find()->where(['id'=>$budget->section_budget_id])->one();
            $approve = ActivityDailyResponsibility::find()->where(['activity_id'=>$modelSection->id])->one();
            $departBudget = ActivityDailyBudgetSection::find()->where(['activity_id'=>$modelSection->id])->one();

            $modelSection->department_status=2;
            $modelSection->save(false);

            $baru->section_budget_value=$baru->section_budget_value+$budget->budget_value_sum;
            $baru->save();

        Yii::$app->getSession()->setFlash('info', 'Kegiatan Berhasil Ditolak');
        return $this->redirect(['index']);

        }

        return $this->render('_form', [
            'model' => $model,
        ]);
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
