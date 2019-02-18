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
            'query' => Activity::find()->where(['done'=> 0])->andWhere(['chief_status'=>1])->orWhere(['department_status'=>1]),
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
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionApply($id)
    {
        $model = Activity::find()->where(['id'=>$id])->one();
        $model->finance_status = 1;
        $model->save(false);
        $status = $model->finance_status;
        Yii::$app->getSession()->setFlash('success', 'Kegiatan Rutin Berhasil Disetujui');
        return $this->redirect(Yii::$app->request->referrer);
        return $this->render([
            'model' => $model,
            'status' => $status
        ]);
    }

    public function actionUpdateApply($id)
    {
        $model = Activity::find()->where(['id'=>$id])->one();
        $model->finance_status = 0;
        $model->save(false);
        $status = $model->finance_status;
        Yii::$app->getSession()->setFlash('info', 'Kegiatan Rutin Berhasil Diedit');
        return $this->redirect(Yii::$app->request->referrer);
        return $this->render([
            'model' => $model,
            'status' => $status
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
            $model->message = $model->message;
            $model->activity_id = $id;
            $save = $model->save(false);

            if ($save) {
                $reject = Activity::find()->where(['id'=>$id])->one();
                $reject->done = 1;
                $reject->save(false);
            }

            $roleSekre =  Activity::find()->where(['role'=>4])->one();
            $roleSeksi =  Activity::find()->where(['role'=>8])->one();

            if ($roleSekre) {
                $modelRutin = Activity::find()->where(['id'=>$id])->one();
                $budget = ActivityBudgetSecretariat::find()->where(['activity_id'=>$modelRutin])->one();
                $awal = ActivityBudgetSecretariat::find()->where(['secretariat_budget_id'=>$budget])->one();
                $baru = SecretariatBudget::find()->where(['id'=>$awal])->one();
                $approve = ActivityResponsibility::find()->where(['activity_id'=>$modelRutin])->one();
                $sekreBudget = ActivityBudgetSecretariat::find()->where(['activity_id'=>$modelRutin])->one();
                $mainMember = ActivityMainMember::find()->where(['activity_id'=>$modelRutin])->all();
                $actitvitySection = ActivitySection::find()->where(['activity_id'=>$modelRutin])->one();
                $idActivitySection = ActivitySectionMember::find()->where(['section_activity_id'=>$actitvitySection])->one();
                $actitvitySectionMember = ActivitySectionMember::find()->where(['activity_id'=>$idActivitySection])->one();

                $modelRutin->chief_status=0;
                $modelRutin->department_status=0;
                $modelRutin->save(false);

                if ($approve) {
                    $uploadPath = Yii::getAlias('@backend')."/web/template";
                    $oldfile = $approve->file;
                    $oldPhoto = $approve->photo;
                    unlink($uploadPath.$oldfile);
                    unlink($uploadPath.$oldPhoto);
                    $approve->delete();
                    $sekreBudget->delete();
                    ActivityMainMember::deleteAll(['activity_id'=>$modelRutin]);
                    $actitvitySectionMember->delete();
                    $actitvitySection->delete();
                } else {
                    $sekreBudget->delete();
                    ActivityMainMember::deleteAll(['activity_id'=>$modelRutin]);
                    $actitvitySectionMember->delete();
                    $actitvitySection->delete();
                }

                $baru->secretariat_budget_value=$baru->secretariat_budget_value+$budget->budget_value_dp;
                $baru->save();
            } else if ($roleSeksi) {
                $modelSeksi = Activity::find()->where(['id'=>$id])->one();
                $budget = ActivityBudgetSection::find()->where(['activity_id'=>$modelSeksi])->one();
                $awal = ActivityBudgetSection::find()->where(['section_budget_id'=>$budget])->one();
                $baru = SectionBudget::find()->where(['id'=>$awal])->one();
                $approve = ActivityResponsibility::find()->where(['activity_id'=>$modelSeksi])->one();
                $seksiBudget = ActivityBudgetSection::find()->where(['activity_id'=>$modelSeksi])->one();
                $mainMember = ActivityMainMember::find()->where(['activity_id'=>$modelRutin])->all();
                $actitvitySection = ActivitySection::find()->where(['activity_id'=>$modelRutin])->one();
                $idActivitySection = ActivitySectionMember::find()->where(['section_activity_id'=>$actitvitySection])->one();
                $actitvitySectionMember = ActivitySectionMember::find()->where(['activity_id'=>$idActivitySection])->one();

                $modelRutin->chief_status=0;
                $modelRutin->department_status=0;
                $modelRutin->save(false);

                if ($approve) {
                    $uploadPath = Yii::getAlias('@backend')."/web/template";
                    $oldfile = $approve->file;
                    $oldPhoto = $approve->photo;
                    unlink($uploadPath.$oldfile);
                    unlink($uploadPath.$oldPhoto);
                    $approve->delete();
                    $seksiBudget->delete();
                    ActivityMainMember::deleteAll(['activity_id'=>$modelRutin]);
                    $actitvitySectionMember->delete();
                    $actitvitySection->delete();
                } else {
                    $seksiBudget->delete();
                    ActivityMainMember::deleteAll(['activity_id'=>$modelRutin]);
                    $actitvitySectionMember->delete();
                    $actitvitySection->delete();
                }

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
