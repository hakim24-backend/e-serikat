<?php

namespace backend\controllers;

use Yii;
use common\models\Activity;
use common\models\ActivityResponsibility;
use common\models\ActivityBudgetChief;
use common\models\ActivityBudgetDepartment;
use common\models\ActivityBudgetSection;
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
use yii\data\ArrayDataProvider;
use common\models\ActivityDaily;
/**
 * ChiefApprovalActivityResponsibilityController implements the CRUD actions for Activity model.
 */
class ChiefApprovalActivityResponsibilityController extends Controller
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
     * Lists all Activity models.
     * @return mixed
     */
    public function actionIndex()
    {
      $role = Yii::$app->user->identity->role;
      $id_chief = Yii::$app->user->identity->chief->id;

      $dataA = Activity::find()
                ->joinWith('activityResponsibilities')
                ->where(['activity.chief_status'=>1])
                // ->andWhere(['role' => $role])
                ->andWhere(['chief_code_id'=>$id_chief])
                ->andWhere(['activity_responsibility.responsibility_value'=>1])
                ->andWhere(['activity.done'=>0])
                  ->asArray()->all();

                  $typeA = array(
                    'tipe' => 'kegiatan',
                  );
                  //
                  foreach ($dataA as $key => $data) {
                    array_splice($dataA[$key], 0, 0 , $typeA);
                  }

        $dataB = ActivityDaily::find()
                  ->joinWith('activityDailyResponsibilities')
                  ->where(['activity_daily.chief_status'=>1])
                  // ->andWhere(['role' => $role])
                  ->andWhere(['chief_code_id'=>$id_chief])
                  ->andWhere(['activity_daily_responsibility.responsibility_value'=>1])
                  ->andWhere(['activity_daily.done'=>0])
                  ->asArray()->all();

                  $typeB = array(
                    'tipe' => 'rutin',
                  );
                  //
                  foreach ($dataB as $key => $data) {
                    array_splice($dataB[$key], 0, 0 , $typeB);
                  }

                  $data = array_merge($dataA, $dataB);

                  $dataProvider = new ArrayDataProvider([
                    'allModels' => $data
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
        $model = ActivityResponsibility::find()->where(['activity_id'=>$id])->one();
        $role = Activity::find()->where(['id'=>$id])->one();

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
     * Creates a new Activity model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionClosing($id)
    {
        $model = Activity::find()->where(['id'=>$id])->one();
        $responsibility = ActivityResponsibility::find()->where(['activity_id'=>$model->id])->one();

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

    public function actionDownload($id)
    {
        $download = ActivityResponsibility::findOne($id);
        $path=Yii::getAlias('@backend').'/web/template'.$download->file;

        if (file_exists($path)) {
            return Yii::$app->response->sendFile($path);
        }
    }

    public function actionReport($id) {

    $report = Activity::find()->where(['id'=>$id])->one();

    if ($report->role == 7) {
        $model = Activity::find()->where(['id'=>$id])->one();
        $budget = ActivityBudgetDepartment::find()->where(['activity_id'=>$model->id])->one();
        $awal = ActivityBudgetDepartment::find()->where(['department_budget_id'=>$budget])->one();
        $baru = DepartmentBudget::find()->where(['id'=>$awal->department_budget_id])->one();
        $sekre = Department::find()->where(['id'=>$baru])->one();
        $sumber = Budget::find()->where(['id'=>$baru])->one();
        $lpj = ActivityResponsibility::find()->where(['activity_id'=>$model->id])->one();
    } elseif ($report->role == 8) {
        $model = Activity::find()->where(['id'=>$id])->one();
        $budget = ActivityBudgetSection::find()->where(['activity_id'=>$model->id])->one();
        $awal = ActivityBudgetSection::find()->where(['section_budget_id'=>$budget])->one();
        $baru = SectionBudget::find()->where(['id'=>$awal->section_budget_id])->one();
        $sekre = Section::find()->where(['id'=>$baru])->one();
        $sumber = Budget::find()->where(['id'=>$baru])->one();
        $lpj = ActivityResponsibility::find()->where(['activity_id'=>$model->id])->one();
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
