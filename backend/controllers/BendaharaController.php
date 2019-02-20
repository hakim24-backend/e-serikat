<?php

namespace backend\controllers;

use Yii;
use common\models\ActivityReject;
use common\models\Activity;
use common\models\ActivityDaily;
use common\models\Budget;
use common\models\Secretariat;
use common\models\Section;
use common\models\ActivityResponsibility;
use common\models\ActivityDailyResponsibility;
use common\models\ActivityBudgetSecretariat;
use common\models\ActivityBudgetSection;
use common\models\ActivityDailyBudgetSection;
use common\models\ActivityMainMember;
use common\models\ActivitySection;
use common\models\ActivitySectionMember;
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
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use kartik\mpdf\Pdf;
use yii\web\UploadedFile;
use common\models\ActivityBudgetChief;
use yii\helpers\ArrayHelper;

/**
 * BendaharaController implements the CRUD actions for ActivityResponsibility model.
 */
class BendaharaController extends Controller
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
     * Lists all ActivityResponsibility models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Activity::find()->where(['finance_status'=> 0]),
        ]);
        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ActivityResponsibility model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
      $role = Yii::$app->user->identity->role;

      // retrieve existing Deposit data
      $model = Activity::find()->where(['id' => $id])->one();

      $ketua = ActivityMainMember::find()
          ->where(['activity_id' => $id])
          ->andWhere(['name_committee' => "Ketua"])->one();
      $wakil = ActivityMainMember::find()
          ->where(['activity_id' => $id])
          ->andWhere(['name_committee' => "Wakil"])
          ->one();
      $sekretaris = ActivityMainMember::find()
          ->where(['activity_id' => $id])
          ->andWhere(['name_committee' => "Sekretaris"])
          ->one();
      $bendahara = ActivityMainMember::find()
          ->where(['activity_id' => $id])
          ->andWhere(['name_committee' => "Bendahara"])
          ->one();

      if ($model->role == 6) {
          $budget = ActivityBudgetChief::find()->where(['activity_id' => $model->id])->one();
          $awal = ActivityBudgetChief::find()->where(['chief_budget_id' => $budget])->one();
          $baru = ChiefBudget::find()->where(['id' => $awal])->one();
          $range = $model->date_start . ' to ' . $model->date_end;
          $range_start = $model->date_start;
          $range_end = $model->date_end;
          $oldDP = $budget->budget_value_dp;
          $oldBudget = $baru->chief_budget_value;
      } else if ($model->role == 8) {
          $budget = ActivityBudgetSection::find()->where(['activity_id' => $model->id])->one();
          $awal = ActivityBudgetSection::find()->where(['section_budget_id' => $budget])->one();
          $baru = SectionBudget::find()->where(['id' => $awal])->one();
          $range = $model->date_start . ' to ' . $model->date_end;
          $range_start = $model->date_start;
          $range_end = $model->date_end;
          $oldDP = $budget->budget_value_dp;
          $oldBudget = $baru->section_budget_value;
      }

      // retrieve existing ActivitySection data
      $oldActivitySectionIds = ActivitySection::find()->select('id')
          ->where(['activity_id' => $id])->asArray()->all();
      $oldActivitySectionIds = ArrayHelper::getColumn($oldActivitySectionIds, 'id');
      $modelsSection = ActivitySection::findAll(['id' => $oldActivitySectionIds]);
      $modelsSection = (empty($modelsSection)) ? [new ActivitySection] : $modelsSection;

      // retrieve existing Loads data
      $oldLoadIds = [];
      foreach ($modelsSection as $i => $modelSection) {
          $oldLoads = ActivitySectionMember::findAll(['section_activity_id' => $modelSection->id]);
          $modelsMember[$i] = $oldLoads;
          $oldLoadIds = array_merge($oldLoadIds, ArrayHelper::getColumn($oldLoads, 'id'));
          $modelsMember[$i] = (empty($modelsMember[$i])) ? [new ActivitySectionMember] : $modelsMember[$i];
      }


        return $this->render('view', [
            'model' => $model,
            'budget' => $budget,
            'baru' => $baru,
            'ketua' => $ketua,
            'wakil' => $wakil,
            'sekretaris' => $sekretaris,
            'bendahara' => $bendahara,
            'modelsSection' => $modelsSection,
            'modelsMember' => $modelsMember,
            'range' => $range,
            'range_start' => $range_start,
            'range_end' => $range_end,
        ]);
    }

    public function actionApply($id)
    {
        $model = Activity::find()->where(['id'=>$id])->one();
        $model->finance_status = 1;
        $model->save(false);
        Yii::$app->getSession()->setFlash('success', 'Kegiatan Rutin Berhasil Disetujui');
        return $this->redirect(Yii::$app->request->referrer);
        return $this->render([
            'model' => $model,
        ]);
    }

    /**
     * Creates a new ActivityDailyReject model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionReject($id)
    {
        $model = new ActivityReject();
        $reject = Activity::find()->where(['id'=>$id])->one();

        if ($model->load(Yii::$app->request->post())) {
            $model->activity_id = $id;
            $model->save(false);

            if ($reject->role == 4) {
                $modelRutin = Activity::find()->where(['id'=>$id])->one();
                $budget = ActivityBudgetSecretariat::find()->where(['activity_id'=>$modelRutin])->one();
                $awal = ActivityBudgetSecretariat::find()->where(['secretariat_budget_id'=>$budget])->one();
                $baru = SecretariatBudget::find()->where(['id'=>$awal])->one();

                $modelRutin->finance_status=2;
                $modelRutin->save(false);

                $baru->secretariat_budget_value=$baru->secretariat_budget_value+$budget->budget_value_dp;
                $baru->save(false);

            } else if ($reject->role == 8) {
                $modelSeksi = Activity::find()->where(['id'=>$id])->one();
                $budget = ActivityBudgetSection::find()->where(['activity_id'=>$modelSeksi])->one();
                $awal = ActivityBudgetSection::find()->where(['section_budget_id'=>$budget])->one();
                $baru = SectionBudget::find()->where(['id'=>$awal])->one();

                $modelSeksi->finance_status = 2;
                $modelSeksi->save(false);

                $baru->section_budget_value=$baru->section_budget_value+$budget->budget_value_dp;
                $baru->save(false);

            }else if ($reject->role == 6) {
                $modelChief = Activity::find()->where(['id'=>$id])->one();
                $budget = ActivityBudgetChief::find()->where(['activity_id'=>$modelChief])->one();
                $awal = ActivityBudgetChief::find()->where(['chief_budget_id'=>$budget])->one();
                $baru = ChiefBudget::find()->where(['id'=>$awal])->one();

                $modelChief->finance_status = 2;
                $modelChief->save(false);

                $baru->chief_budget_value=$baru->chief_budget_value+$budget->budget_value_dp;
                $baru->save(false);
            }else if ($reject->role == 7) {
                $modelDep = Activity::find()->where(['id'=>$id])->one();
                $budget = ActivityBudgetDepartment::find()->where(['activity_id'=>$modelDep])->one();
                $awal = ActivityBudgetDepartment::find()->where(['department_budget_id'=>$budget])->one();
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
     * Finds the ActivityResponsibility model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ActivityResponsibility the loaded model
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
