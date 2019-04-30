<?php

namespace backend\controllers;

use Yii;
use common\models\ActivityDaily;
use common\models\ActivityDailyBudgetChief;
use common\models\ActivityDailyBudgetDepart;
use common\models\ActivityDailyBudgetSection;
use common\models\ActivityDailyResponsibility;
use common\models\DepartmentBudget;
use common\models\SectionBudget;
use common\models\ChiefBudget;
use common\models\Section;
use common\models\Chief;
use common\models\Department;
use common\models\Budget;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use kartik\mpdf\Pdf;

/**
 * ChiefApprovalActivityDailyResponsibilityController implements the CRUD actions for ActivityDaily model.
 */
class ChiefApprovalActivityDailyResponsibilityController extends Controller
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
                        'actions' => ['logout','index','view','closing','update-closing','delete','download','report'],
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
     * Lists all ActivityDaily models.
     * @return mixed
     */
    public function actionIndex()
    {

      $role = Yii::$app->user->identity->role;
      $id_chief = Yii::$app->user->identity->chief->id;

        $dataProvider = new ActiveDataProvider([
            'query' => ActivityDaily::find()
                      ->joinWith('activityDailyResponsibilities')
                      ->where(['activity_daily.chief_status'=>1])
                      // ->andWhere(['role' => $role])
                      ->andWhere(['chief_code_id'=>$id_chief])
                      ->andWhere(['activity_daily_responsibility.responsibility_value'=>1])
                      ->andWhere(['activity_daily.done'=>0]),
        ]);
        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ActivityDaily model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $role = ActivityDaily::find()->where(['id'=>$id])->one();
        $model = ActivityDailyResponsibility::find()->where(['activity_id'=>$id])->one();
        if ($model != null) {
            return $this->render('view', [
            'model' => $model,
            'role'=> $role
        ]);
        } else {
            Yii::$app->getSession()->setFlash('warning', 'Data Pertangungjawaban Tidak Ada');
            return $this->redirect(Yii::$app->request->referrer);
        }
    }

    /**
     * Creates a new ActivityDaily model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionClosing($id)
    {
        $model = ActivityDaily::find()->where(['id'=>$id])->one();
        $responsibility = ActivityDailyResponsibility::find()->where(['activity_id'=>$model->id])->one();

        if ($responsibility == null) {
            Yii::$app->getSession()->setFlash('warning', 'Tidak Dapat Approve Pertangungjawaban Karena Data Pertangungjawaban Tidak Ada');
            return $this->redirect(Yii::$app->request->referrer);
        } else {
            $responsibility->responsibility_value = 2;
            $responsibility->save(false);
        }

        Yii::$app->getSession()->setFlash('info', 'Kegiatan Selesai');
        return $this->redirect(Yii::$app->request->referrer);

        return $this->render([
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing ActivityDaily model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdateClosing($id)
    {
        $model = ActivityDaily::find()->where(['id'=>$id])->one();
        $model->done = 0;
        $model->save(false);

        $responsibility = ActivityDailyResponsibility::find()->where(['activity_id'=>$model])->one();
        $responsibility->responsibility_value = 0;
        $responsibility->save(false);

        Yii::$app->getSession()->setFlash('warning', 'Kegiatan Selesai');
        return $this->redirect(Yii::$app->request->referrer);
        return $this->render([
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing ActivityDaily model.
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

    if ($report->role == 7) {
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
        $sekre = Section::find()->where(['id'=>$baru->section_budget_id])->one();
        $sumber = Budget::find()->where(['id'=>$baru->section_id])->one();
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

    /**
     * Finds the ActivityDaily model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ActivityDaily the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ActivityDaily::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
