<?php

namespace backend\controllers;

use Yii;
use common\models\ActivityDaily;
use common\models\Budget;
use common\models\Secretariat;
use common\models\Section;
use common\models\Department;
use common\models\Chief;
use common\models\ActivityResponsibility;
use common\models\ActivityDailyResponsibility;
use common\models\ActivityDailyBudgetSecretariat;
use common\models\ActivityDailyBudgetSection;
use common\models\ActivityDailyBudgetChief;
use common\models\ActivityDailyBudgetDepart;
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

class BendaharaActivityDailyResponsibilityController extends \yii\web\Controller
{
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
                        'actions' => ['logout','index','closing','view','download','report'],
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

    public function actionIndex()
    {
      	$dataProvider = new ActiveDataProvider([
            'query' => ActivityDaily::find()
                      ->joinWith('activityDailyResponsibilities')
                      ->where(['activity_daily.finance_status'=>1])
                      ->andWhere(['or',
                      ['activity_daily_responsibility.responsibility_value'=>2],
                      ['activity_daily_responsibility.responsibility_value'=>3],
                    ]),

        ]);
        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionClosing($id)
    {
    	$model = ActivityDaily::find()->where(['id'=>$id])->one();
        $responsibility = ActivityDailyResponsibility::find()->where(['activity_id'=>$model->id])->one();

        if ($responsibility == null) {
            Yii::$app->getSession()->setFlash('warning', 'Tidak Dapat Approve Pertangungjawaban Karena Data Pertangungjawaban Tidak Ada');
            return $this->redirect(Yii::$app->request->referrer);
        } else {
            if ($model->role == 4) {
            $modelRutin = ActivityDaily::find()->where(['id'=>$id])->one();
            $budget = ActivityDailyBudgetSecretariat::find()->where(['activity_id'=>$modelRutin->id])->one();
            $baru = SecretariatBudget::find()->where(['id'=>$budget->secretariat_budget_id])->one();
            $realOutput = $budget->budget_value_sum - $budget->budget_value_dp;

            $baru->secretariat_budget_value=$baru->secretariat_budget_value+$realOutput;
            $baru->save();
        } else if ($model->role == 8) {
            $modelSeksi = ActivityDaily::find()->where(['id'=>$id])->one();
            $budget = ActivityDailyBudgetSection::find()->where(['activity_id'=>$modelSeksi->id])->one();
            $baru = SectionBudget::find()->where(['id'=>$budget->section_budget_id])->one();
            $realOutput = $budget->budget_value_sum - $budget->budget_value_dp;

            $baru->section_budget_value=$baru->section_budget_value+$realOutput;
            $baru->save();
        }else if ($model->role == 6) {
            $modelSeksi = ActivityDaily::find()->where(['id'=>$id])->one();
            $budget = ActivityDailyBudgetChief::find()->where(['activity_id'=>$modelSeksi->id])->one();
            $baru = ChiefBudget::find()->where(['id'=>$budget->chief_budget_id])->one();
            $realOutput = $budget->budget_value_sum - $budget->budget_value_dp;

            $baru->chief_budget_value=$baru->chief_budget_value+$realOutput;
            $baru->save();
        }else if ($model->role == 7) {
            $modelSeksi = ActivityDaily::find()->where(['id'=>$id])->one();
            $budget = ActivityDailyBudgetDepart::find()->where(['activity_id'=>$modelSeksi->id])->one();
            $baru = DepartmentBudget::find()->where(['id'=>$budget->department_budget_id])->one();
            $realOutput = $budget->budget_value_sum - $budget->budget_value_dp;

            $baru->department_budget_value=$baru->department_budget_value+$realOutput;
            $baru->save();
        }

            $model->done = 1;
            $model->save(false);
            $responsibility->responsibility_value = 3;
            $responsibility->save(false);
        }

        Yii::$app->getSession()->setFlash('info', 'Kegiatan Berhasil Di Tutup');
        return $this->redirect(Yii::$app->request->referrer);
        return $this->render([
            'model' => $model,
        ]);

    }

    public function actionView($id)
    {
        $role = ActivityDaily::find()->where(['id'=>$id])->one();
        $model = ActivityDailyResponsibility::find()->where(['activity_id'=>$id])->one();
        if ($model != null) {
            return $this->render('view', [
            'model' => $model,
            'role' => $role
        ]);
        } else {
            Yii::$app->getSession()->setFlash('warning', 'Data Pertangungjawaban Tidak Ada');
            return $this->redirect(Yii::$app->request->referrer);
        }
    }

    public function actionDownload($id)
    {
        $download = ActivityDailyResponsibility::findOne($id);
        $path=Yii::getAlias('@backend').'/web/template'.$download->file;

        if (file_exists($path)) {
            return Yii::$app->response->sendFile($path);
        }
    }

    public function actionReport($id) {

    $report = ActivityDaily::find()->where(['id'=>$id])->one();

    if ($report->role == 4) {
        $model = ActivityDaily::find()->where(['id'=>$id])->one();
        $budget = ActivityDailyBudgetSecretariat::find()->where(['activity_id'=>$model->id])->one();
        $baru = SecretariatBudget::find()->where(['id'=>$budget->secretariat_budget_id])->one();
        $sekre = Secretariat::find()->where(['id'=>$baru->secretariat_id])->one();
        $sumber = Budget::find()->where(['id'=>$baru->secretariat_budget_id])->one();
        $lpj = ActivityDailyResponsibility::find()->where(['activity_id'=>$model->id])->one();
    }else if ($report->role == 6) {
        $model = ActivityDaily::find()->where(['id'=>$id])->one();
        $budget = ActivityDailyBudgetChief::find()->where(['activity_id'=>$model->id])->one();
        $baru = ChiefBudget::find()->where(['id'=>$budget->chief_budget_id])->one();
        $sekre = Chief::find()->where(['id'=>$baru->chief_id])->one();
        $sumber = Budget::find()->where(['id'=>$baru->chief_budget_id])->one();
        $lpj = ActivityDailyResponsibility::find()->where(['activity_id'=>$model->id])->one();
    }else if ($report->role == 7) {
        $model = ActivityDaily::find()->where(['id'=>$id])->one();
        $budget = ActivityDailyBudgetDepart::find()->where(['activity_id'=>$model->id])->one();
        $baru = DepartmentBudget::find()->where(['id'=>$budget->department_budget_id])->one();
        $sekre = Department::find()->where(['id'=>$baru->department_id])->one();
        $sumber = Budget::find()->where(['id'=>$baru->department_budget_id])->one();
        $lpj = ActivityDailyResponsibility::find()->where(['activity_id'=>$model->id])->one();
    } elseif ($report->role == 8) {
        $model = ActivityDaily::find()->where(['id'=>$id])->one();
        $budget = ActivityDailyBudgetSection::find()->where(['activity_id'=>$model->id])->one();
        $baru = SectionBudget::find()->where(['id'=>$budget->section_budget_id])->one();
        $sekre = Section::find()->where(['id'=>$baru->section_id])->one();
        $sumber = Budget::find()->where(['id'=>$baru->section_budget_id])->one();
        $lpj = ActivityDailyResponsibility::find()->where(['activity_id'=>$model->id])->one();
    }

    $content = $this->renderPartial('view_pdf',[
            'model'=>$model,
            'budget'=>$budget,
            'baru'=>$baru,
            'sumber'=>$sumber,
            'sekre'=>$sekre,
            'report'=>$report,
            'lpj'=> $lpj
        ]);

        // setup kartik\mpdf\Pdf component
        $pdf = new Pdf([
            // set to use core fonts only
            'mode' => Pdf::MODE_CORE,
            // A4 paper format
            'format' => Pdf::FORMAT_A4,
            // portrait orientation
            'orientation' => Pdf::ORIENT_PORTRAIT,
            // stream to browser inline
            'destination' => Pdf::DEST_BROWSER,
            // your html content input
            'content' => $content,
            // format content from your own css file if needed or use the
            // enhanced bootstrap css built by Krajee for mPDF formatting
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',
            // any css to be embedded if required
            'cssInline' => '.kv-heading-1{font-size:18px}',
             // set mPDF properties on the fly
            'options' => ['title' => 'Krajee Report Title'],
             // call mPDF methods on the fly
            'methods' => [
                // 'SetHeader'=>['Krajee Report Header'],
                'SetFooter'=>['{PAGENO}'],
            ]
        ]);
    // return the pdf output as per the destination setting
    return $pdf->render();
    }

}
