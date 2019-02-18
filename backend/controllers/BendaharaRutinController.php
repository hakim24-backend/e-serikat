<?php

namespace backend\controllers;

use Yii;
use common\models\ActivityDailyReject;
use common\models\ActivityDaily;
use common\models\Budget;
use common\models\Secretariat;
use common\models\Section;
use common\models\ActivityResponsibility;
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
        'query' => ActivityDaily::find()->where(['done'=> 0])->andWhere(['chief_status'=>1])->orWhere(['department_status'=>1]),
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
        $model = ActivityDaily::find()->where(['id'=>$id])->one();
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

    public function actionView($id)
    {
        $model = ActivityDaily::find()->where(['id'=>$id])->one();
        return $this->render('view', [
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
        $model = new ActivityDailyReject();
        $reject = ActivityDaily::find()->where(['id'=>$id])->one();

        if ($model->load(Yii::$app->request->post())) {
            $model->message = $model->message;
            $model->activity_id = $id;
            $save = $model->save(false);

            if ($save) {
                $reject = ActivityDaily::find()->where(['id'=>$id])->one();
                $reject->done = 1;
                $reject->save(false);
            }

            $roleSekre =  ActivityDaily::find()->where(['role'=>4])->one();
            $roleSeksi =  ActivityDaily::find()->where(['role'=>8])->one();

            if ($roleSekre) {
                $modelRutin = ActivityDaily::find()->where(['id'=>$id])->one();
                $budget = ActivityDailyBudgetSecretariat::find()->where(['activity_id'=>$modelRutin])->one();
                $awal = ActivityDailyBudgetSecretariat::find()->where(['secretariat_budget_id'=>$budget])->one();
                $baru = SecretariatBudget::find()->where(['id'=>$awal])->one();
                $approve = ActivityDailyResponsibility::find()->where(['activity_id'=>$modelRutin])->one();
                $sekreBudget = ActivityDailyBudgetSecretariat::find()->where(['activity_id'=>$modelRutin])->one();

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
                } else {
                    $sekreBudget->delete();
                }

                $baru->secretariat_budget_value=$baru->secretariat_budget_value+$budget->budget_value_dp;
                $baru->save();
            } else if ($roleSeksi) {
                $modelSeksi = ActivityDaily::find()->where(['id'=>$id])->one();
                $budget = ActivityDailyBudgetSection::find()->where(['activity_id'=>$modelSeksi])->one();
                $awal = ActivityDailyBudgetSection::find()->where(['section_budget_id'=>$budget])->one();
                $baru = SectionBudget::find()->where(['id'=>$awal])->one();
                $approve = ActivityDailyResponsibility::find()->where(['activity_id'=>$modelSeksi])->one();
                $seksiBudget = ActivityDailyBudgetSection::find()->where(['activity_id'=>$modelSeksi])->one();

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
                } else {
                    $seksiBudget->delete();
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
