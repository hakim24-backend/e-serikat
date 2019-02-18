<?php

namespace backend\controllers;

use Yii;
use common\models\Activity;
use common\models\ActivityDaily;
use common\models\Budget;
use common\models\Secretariat;
use common\models\Section;
use common\models\ActivityResponsibility;
use common\models\ActivityDailyResponsibility;
use common\models\ActivityDailyBudgetSecretariat;
use common\models\ActivityDailyBudgetSection;
use common\models\ActivityBudgetSecretariat;
use common\models\ActivityBudgetSection;
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

/**
 * BendaharaActivityResponsibilityController implements the CRUD actions for ActivityResponsibility model.
 */
class BendaharaActivityResponsibilityController extends Controller
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
                        'actions' => ['logout','index','closing','update','delete','view','download'],
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
            'query' => Activity::find()->where(['finance_status'=>1])->andWhere(['done'=>0]),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionClosing($id)
    {
        $model = Activity::find()->where(['id'=>$id])->one();
        $responsibility = ActivityResponsibility::find()->where(['activity_id'=>$model])->one();

        if ($responsibility == null) {
            Yii::$app->getSession()->setFlash('warning', 'Tidak Dapat Approve Pertangungjawaban Karena Data Pertangungjawaban Tidak Ada');
            return $this->redirect(Yii::$app->request->referrer);
        } else {
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

    /**
     * Updates an existing ActivityResponsibility model.
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
     * Deletes an existing ActivityResponsibility model.
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

    public function actionView($id)
    {
        $model = ActivityResponsibility::find()->where(['activity_id'=>$id])->one();
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
        $download = ActivityResponsibility::findOne($id);
        $path=Yii::getAlias('@backend').'/web/template'.$download->file;

        if (file_exists($path)) {
            return Yii::$app->response->sendFile($path);
        }
    }

    // public function actionReport($id) {

    // $roleSekre =  Activity::find()->where(['role'=>4])->one();
    // $roleSeksi =  Activity::find()->where(['role'=>8])->one();

    // if ($roleSekre) {
    //     $model = Activity::find()->where(['id'=>$id])->one();
    //     $budget = ActivityBudgetSecretariat::find()->where(['activity_id'=>$model])->one();
    //     $awal = ActivityBudgetSecretariat::find()->where(['secretariat_budget_id'=>$budget])->one();
    //     $baru = SecretariatBudget::find()->where(['id'=>$awal])->one();
    //     $sekre = Secretariat::find()->where(['id'=>$baru])->one();
    //     $sumber = Budget::find()->where(['id'=>$baru])->one();
    // } else if ($roleSeksi) {
    //     $model = Activity::find()->where(['id'=>$id])->one();
    //     $budget = ActivityBudgetSection::find()->where(['activity_id'=>$model])->one();
    //     $awal = ActivityBudgetSection::find()->where(['section_budget_id'=>$budget])->one();
    //     $baru = SectionBudget::find()->where(['id'=>$awal])->one();
    //     $sekre = Section::find()->where(['id'=>$baru])->one();
    //     $sumber = Budget::find()->where(['id'=>$baru])->one();
    // }

    //     $content = $this->renderPartial('view_pdf',[
    //         'model'=>$model,
    //         'budget'=>$budget,
    //         'baru'=>$baru,
    //         'sumber'=>$sumber,
    //         'sekre'=>$sekre
    //     ]);

    //     // setup kartik\mpdf\Pdf component
    //     $pdf = new Pdf([
    //         // set to use core fonts only
    //         'mode' => Pdf::MODE_CORE,
    //         // A4 paper format
    //         'format' => Pdf::FORMAT_A4,
    //         // portrait orientation
    //         'orientation' => Pdf::ORIENT_PORTRAIT,
    //         // stream to browser inline
    //         'destination' => Pdf::DEST_BROWSER,
    //         // your html content input
    //         'content' => $content,
    //         // format content from your own css file if needed or use the
    //         // enhanced bootstrap css built by Krajee for mPDF formatting
    //         'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',
    //         // any css to be embedded if required
    //         'cssInline' => '.kv-heading-1{font-size:18px}',
    //          // set mPDF properties on the fly
    //         'options' => ['title' => 'Krajee Report Title'],
    //          // call mPDF methods on the fly
    //         'methods' => [
    //             // 'SetHeader'=>['Krajee Report Header'],
    //             'SetFooter'=>['{PAGENO}'],
    //         ]
    //     ]);
    // // return the pdf output as per the destination setting
    // return $pdf->render();
    // }

    /**
     * Finds the ActivityResponsibility model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ActivityResponsibility the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ActivityResponsibility::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
