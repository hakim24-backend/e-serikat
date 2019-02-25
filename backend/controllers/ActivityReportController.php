<?php

namespace backend\controllers;

use Yii;
use common\models\Activity;
use common\models\ActivityMainMember;
use common\models\ActivityBudgetChief;
use common\models\ActivityBudgetSection;
use common\models\ActivityBudgetDepartment;
use common\models\ActivityBudgetSecretariat;
use common\models\ChiefBudget;
use common\models\SectionBudget;
use common\models\SecretariatBudget;
use common\models\DepartmentBudget;
use common\models\ActivitySection;
use common\models\ActivitySectionMember;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

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

      $role = Yii::$app->user->identity->role;
      // retrieve existing Deposit data
      $model = Activity::find()->where(['id' => $id])->one();

      $ketua = ActivityMainMember::find()
          ->where(['activity_id' => $id])
          ->andWhere(['name_committee' => "Ketua"])->one();
          // var_dump($ketua->name_member);die;
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

      if ($model->role == 4) {
          $budget = ActivityBudgetSecretariat::find()->where(['activity_id' => $model->id])->one();
          $awal = ActivityBudgetSecretariat::find()->where(['secretariat_budget_id' => $budget])->one();
          $baru = SecretariatBudget::find()->where(['id' => $awal])->one();
          $range = $model->date_start . ' to ' . $model->date_end;
          $range_start = $model->date_start;
          $range_end = $model->date_end;
          $oldDP = $budget->budget_value_dp;
          $oldBudget = $baru->secretariat_budget_value;
      } elseif ($model->role == 6) {
          $budget = ActivityBudgetChief::find()->where(['activity_id' => $model->id])->one();
          $awal = ActivityBudgetChief::find()->where(['chief_budget_id' => $budget])->one();
          $baru = ChiefBudget::find()->where(['id' => $awal])->one();
          $range = $model->date_start . ' to ' . $model->date_end;
          $range_start = $model->date_start;
          $range_end = $model->date_end;
          $oldDP = $budget->budget_value_sum;
          $oldBudget = $baru->chief_budget_value;
      } elseif ($model->role == 7) {
          $budget = ActivityBudgetDepartment::find()->where(['activity_id' => $model->id])->one();
          $awal = ActivityBudgetDepartment::find()->where(['department_budget_id' => $budget])->one();
          $baru = DepartmentBudget::find()->where(['id' => $awal])->one();
          $range = $model->date_start . ' to ' . $model->date_end;
          $range_start = $model->date_start;
          $range_end = $model->date_end;
          $oldDP = $budget->budget_value_sum;
          $oldBudget = $baru->department_budget_value;
      } elseif ($model->role == 8) {
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
