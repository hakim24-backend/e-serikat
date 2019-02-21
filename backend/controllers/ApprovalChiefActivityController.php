<?php

namespace backend\controllers;

use Yii;
use common\models\Activity;
use common\models\ActivityReject;
use common\models\ActivitySection;
use common\models\ActivitySectionMember;
use common\models\ActivityResponsibility;
use common\models\ActivityBudgetDepartment;
use common\models\ActivityBudgetSection;
use common\models\DepartmentBudget;
use common\models\ActivityMainMember;
use common\models\ActivityBudgetChief;
use common\models\ChiefBudget;
use common\models\SectionBudget;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

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
