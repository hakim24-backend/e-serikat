<?php

namespace backend\controllers;

use Yii;
use common\models\ActivityDailyReject;
use common\models\ActivityDaily;
use common\models\Budget;
use common\models\Secretariat;
use common\models\Section;
use common\models\ActivityResponsibility;
use common\models\ActivityDailyBudgetDepart;
use common\models\ActivityDailyResponsibility;
use common\models\ActivityDailyBudgetSecretariat;
use common\models\ActivityDailyBudgetSection;
use common\models\Approve;
use common\models\User;
use common\models\TransferRecord;
use common\models\SecretariatBudget;
use common\models\ChiefBudget;
use common\models\DepartmentBudget;
use common\models\SectionBudget;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use kartik\mpdf\Pdf;
use yii\web\UploadedFile;
use common\models\ActivityMainMember;
use common\models\ActivitySection;
use common\models\ActivitySectionMember;
use common\models\ActivityDailyBudgetChief;
use yii\helpers\ArrayHelper;

/**
 * BendaharaController implements the CRUD actions for ActivityDailyReject model.
 */
class BendaharaRutinController extends Controller
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
                        'actions' => ['logout','index','apply','update-apply','view','reject','delete'],
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
     * Lists all ActivityDailyReject models.
     * @return mixed
     */
    public function actionIndex()
    {

        $dataProvider = new ActiveDataProvider([
        'query' => ActivityDaily::find()->where(['finance_status'=> 0]),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ActivityDailyReject model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionApply($id)
    {
        $model = ActivityDaily::find()->where(['id'=>$id])->one();
        $model->finance_status = 1;

        if($model->save(false)){
          Yii::$app->getSession()->setFlash('success', 'Kegiatan Rutin Berhasil Disetujui');
          return $this->redirect(Yii::$app->request->referrer);
        }

        return $this->render([
            'model' => $model,
        ]);
    }

    public function actionView($id)
    {
      $role = Yii::$app->user->identity->role;

      // retrieve existing Deposit data
      $model = ActivityDaily::find()->where(['id' => $id])->one();
      // var_dump($model->role);die;

      if ($model->role == 6) {
          $budget = ActivityDailyBudgetChief::find()->where(['activity_id' => $model])->one();
          $awal = ActivityDailyBudgetChief::find()->where(['chief_budget_id' => $budget])->one();
          $baru = ChiefBudget::find()->where(['id' => $awal])->one();
          $range = $model->date_start . ' to ' . $model->date_end;
          $range_start = $model->date_start;
          $range_end = $model->date_end;
          $oldDP = $budget->budget_value_dp;
          $oldBudget = $baru->chief_budget_value;
      }

      if ($model->role == 7) {
          $budget = ActivityDailyBudgetDepart::find()->where(['activity_id' => $model])->one();
          $awal = ActivityDailyBudgetDepart::find()->where(['department_budget_id' => $budget])->one();
          $baru = DepartmentBudget::find()->where(['id' => $awal])->one();
          $range = $model->date_start . ' to ' . $model->date_end;
          $range_start = $model->date_start;
          $range_end = $model->date_end;
          $oldDP = $budget->budget_value_dp;
          $oldBudget = $baru->department_budget_value;
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
     * Creates a new ActivityDailyReject model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionReject($id)
    {
        $model = new ActivityDailyReject();
        $reject = ActivityDaily::find()->where(['id'=>$id])->one();

        if ($model->load(Yii::$app->request->post())) {
            $model->activity_id = $id;
            $model->save(false);

            if ($reject->role == 4) {
                $modelRutin = ActivityDaily::find()->where(['id'=>$id])->one();
                $budget = ActivityDailyBudgetSecretariat::find()->where(['activity_id'=>$modelRutin])->one();
                $awal = ActivityDailyBudgetSecretariat::find()->where(['secretariat_budget_id'=>$budget])->one();
                $baru = SecretariatBudget::find()->where(['id'=>$awal])->one();

                $modelRutin->finance_status=2;
                $modelRutin->save(false);

                $baru->secretariat_budget_value=$baru->secretariat_budget_value+$budget->budget_value_dp;
                $baru->save();
            } else if ($reject->role == 8) {
                $modelSeksi = ActivityDaily::find()->where(['id'=>$id])->one();
                $budget = ActivityDailyBudgetSection::find()->where(['activity_id'=>$modelSeksi])->one();
                $awal = ActivityDailyBudgetSection::find()->where(['section_budget_id'=>$budget])->one();
                $baru = SectionBudget::find()->where(['id'=>$awal])->one();

                $modelSeksi->finance_status = 2;
                $modelSeksi->save(false);

                $baru->section_budget_value=$baru->section_budget_value+$budget->budget_value_dp;
                $baru->save();
            } else if ($reject->role == 6) {
                $modelChief = ActivityDaily::find()->where(['id'=>$id])->one();
                $budget = ActivityDailyBudgetChief::find()->where(['activity_id'=>$modelChief])->one();
                $awal = ActivityDailyBudgetChief::find()->where(['chief_budget_id'=>$budget])->one();
                $baru = ChiefBudget::find()->where(['id'=>$awal])->one();

                $modelChief->finance_status = 2;
                $modelChief->save(false);

                $baru->chief_budget_value=$baru->chief_budget_value+$budget->budget_value_dp;
                $baru->save(false);
            }else if ($reject->role == 7) {
                $modelDep = ActivityDaily::find()->where(['id'=>$id])->one();
                $budget = ActivityDailyBudgetDepart::find()->where(['activity_id'=>$modelDep])->one();
                $awal = ActivityDailyBudgetDepart::find()->where(['department_budget_id'=>$budget])->one();
                $baru = DepartmentBudget::find()->where(['id'=>$awal])->one();

                $modelDep->finance_status = 2;
                $modelDep->save(false);

                $baru->department_budget_value=$baru->department_budget_value+$budget->budget_value_dp;
                $baru->save(false);
            }

        Yii::$app->getSession()->setFlash('info', 'Kegiatan Berhasil Ditolak');
        return $this->redirect(['index']);

        }

        return $this->render('_form', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing ActivityDailyReject model.
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
     * Finds the ActivityDailyReject model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ActivityDailyReject the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ActivityDailyReject::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
