<?php

namespace backend\controllers;

use Yii;
use common\models\Model;
use common\models\Activity;
use common\models\ActivityMainMember;
use common\models\ActivitySection;
use common\models\ActivitySectionMember;
use common\models\ActivityResponsibility;
use common\models\ActivityBudgetSecretariat;
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

/**
 * KegiatanController implements the CRUD actions for Activity model.
 */
class KegiatanController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
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
            'query' => Activity::find(),
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
    public function actionCreate()
    {
        $model = new Activity();
        $modelsMain = new ActivityMainMember;
        $modelsSection = [new ActivitySection];
        $modelsMember = [[new ActivitySectionMember]];
        if ($model->load(Yii::$app->request->post())) {


              $post = Yii::$app->request->post();
              $model->role = Yii::$app->user->identity->role;
              $model->finance_status = 0;
              $model->department_status = 0;
              $model->chief_status = 0;
              $model->chief_code_id = 0;
              $model->department_code_id = 0;
              $model->done = 0;
              $model->date_start = $post['from_date'];
              $model->date_end = $post['to_date'];
              // var_dump($model->role);die;

              $modelsSection = Model::createMultiple(ActivitySection::classname());
              Model::loadMultiple($modelsSection, Yii::$app->request->post());

              // validate all models
              $valid = $model->validate();
              $valid = Model::validateMultiple($modelsSection) && $valid;

              if ($valid) {
                var_dump("test");die;
                  $transaction = \Yii::$app->db->beginTransaction();
                  try {
                      if ($flag = $model->save(false)) {
                          foreach ($modelsSection as $modelSection) {
                              $modelSection->activity_id = $model->id;
                              if (! ($flag = $modelSection->save(false))) {
                                  $transaction->rollBack();
                                  break;
                              }
                          }

                          if($post){
                            if($post['ketua']){
                              $modelsMain->name_committee = "Ketua";
                              $modelsMain->name_member = $post['ketua'];
                              $modelsMain->activity_id = $model->id;

                              $modelsMain->save();
                            }
                            if($post['wakil']){
                              $modelsMain->name_committee = "Wakil";
                              $modelsMain->name_member = $post['wakil'];
                              $modelsMain->activity_id = $model->id;

                              $modelsMain->save();
                            }
                            if($post['sekretaris']){
                              $modelsMain->name_committee = "Sekretaris";
                              $modelsMain->name_member = $post['sekretaris'];
                              $modelsMain->activity_id = $model->id;

                              $modelsMain->save();
                            }
                            if($post['bendahara']){
                              $modelsMain->name_committee = "Bendahara";
                              $modelsMain->name_member = $post['bendahara'];
                              $modelsMain->activity_id = $model->id;

                              $modelsMain->save();
                            }
                          }


                      }
                      if ($flag) {
                          $transaction->commit();
                          return $this->redirect(['index']);
                      }
                  } catch (Exception $e) {
                      $transaction->rollBack();
                  }

        }

        return $this->render('_form', [
            'model' => $model,
            'modelsSection' => $modelsSection,
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
        $range = $model->date_start.' to '.$model->date_end;
        $range_start = $model->date_start;
        $range_end = $model->date_end;

        if ($model->load(Yii::$app->request->post())) {
            $post = Yii::$app->request->post();
            $model->date_start = $post['from_date'];
            $model->date_end = $post['to_date'];
            $model->save(false);
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
            'range' => $range,
            'range_start' => $range_start,
            'range_end' => $range_end,
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
