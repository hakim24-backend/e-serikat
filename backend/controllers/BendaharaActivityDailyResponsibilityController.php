<?php

namespace backend\controllers;

use Yii;
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
        'query' => ActivityDaily::find()->where(['finance_status'=> 1])->andWhere(['done'=> 0]),
        ]);
        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionClosing($id)
    {
    	$model = ActivityDaily::find()->where(['id'=>$id])->one();
        $responsibility = ActivityDailyResponsibility::find()->where(['activity_id'=>$model])->one();

        if ($responsibility == null) {
            Yii::$app->getSession()->setFlash('warning', 'Tidak Dapat Approve Pertangungjawaban Karena Data Pertangungjawaban Tidak Ada');
            return $this->redirect(Yii::$app->request->referrer);
        } else {
            $model->done = 1;
            $model->save(false);

            $responsibility->responsibility_value = 1;
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
        $model = ActivityDailyResponsibility::find()->where(['activity_id'=>$id])->one();
        if ($model != null) {
            return $this->render('view', [
            'model' => $model,
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

    $roleSekre = ActivityDaily::find()->where(['role'=>4])->one();
    $roleSeksi =  ActivityDaily::find()->where(['role'=>8])->one();
     
    if ($roleSekre) {
            $key=1;
            if ($key == 1) {
                $model = ActivityDaily::find()->where(['id'=>$id])->one();
                $budget = ActivityDailyBudgetSecretariat::find()->where(['activity_id'=>$model])->one();
                $awal = ActivityDailyBudgetSecretariat::find()->where(['secretariat_budget_id'=>$budget])->one();
                $baru = SecretariatBudget::find()->where(['id'=>$awal])->one();
                $sekre = Secretariat::find()->where(['id'=>$baru])->one();
                $sumber = Budget::find()->where(['id'=>$baru])->one();
            }
    } elseif ($roleSeksi) {
            $key=2;
            if ($key == 2) {
                $model = ActivityDaily::find()->where(['id'=>$id])->one();
                $budget = ActivityDailyBudgetSection::find()->where(['activity_id'=>$model])->one();
                $awal = ActivityDailyBudgetSection::find()->where(['section_budget_id'=>$budget])->one();
                $baru = SectionBudget::find()->where(['id'=>$awal])->one();
                $sekre = Section::find()->where(['id'=>$baru])->one();
                $sumber = Budget::find()->where(['id'=>$baru])->one();
            }
    }

    $content = $this->renderPartial('view_pdf',[
            'model'=>$model,
            'budget'=>$budget,
            'baru'=>$baru,
            'sumber'=>$sumber,
            'sekre'=>$sekre,
            'key' => $key,
            'roleSekre'=>$roleSekre,
            'roleSeksi'=>$roleSeksi
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
