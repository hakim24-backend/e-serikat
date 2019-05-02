<?php

namespace backend\controllers;

use Yii;
use common\models\ActivityDailyResponsibility;
use common\models\Approve;
use common\models\ActivityDaily;
use common\models\User;
use common\models\ActivityDailyBudgetSection;
use common\models\ActivityDailyBudgetSecretariat;
use common\models\ActivityDailyBudgetDepart;
use common\models\SectionBudget;
use common\models\SecretariatBudget;
use common\models\DepartmentBudget;
use common\models\Department;
use common\models\Section;
use common\models\Secretariat;
use common\models\Budget;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\UploadedFile;
use kartik\mpdf\Pdf;

/**
 * DepartmentActivityDailyResponsibilityController implements the CRUD actions for ActivityDaily model.
 */
class DepartmentActivityDailyResponsibilityController extends Controller
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
                        'actions' => ['logout','index','view','create','update','delete','report'],
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
        $atasan = Yii::$app->user->identity->department->id_chief;

        $dataProvider = new ActiveDataProvider([
            'query' => ActivityDaily::find()
                        ->joinWith('activityDailyBudgetDeparts')
                        ->joinWith('activityDailyBudgetDeparts.departmentBudget')
                        ->joinWith('activityDailyBudgetDeparts.departmentBudget.department')
                        ->where(['role'=>$role])
                        ->andwhere(['finance_status'=> 1])->andWhere(['department_status'=> 1])->andWhere(['chief_status'=> 1])
                        ->andWhere(['department.id_chief'=>$atasan])
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
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new ActivityDaily model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id)
    {
        $model = new ActivityDailyResponsibility();
        $activity = ActivityDaily::find()->where(['id'=>$id])->one();
        $modelBudget = ActivityDailyBudgetDepart::find()->where(['activity_id'=>$activity->id])->one();
        $baru = DepartmentBudget::find()->where(['id'=>$modelBudget->department_budget_id])->one();

        if ($model->load(Yii::$app->request->post())&&$modelBudget->load(Yii::$app->request->post())) {

            $file_dok = UploadedFile::getInstances($model, 'fileApproves');
            $uploadPath = Yii::getAlias('@backend')."/web/template";
            $acak = substr( md5(time()) , 0, 10);
            $i = 0;
            $tmp = '';
            foreach ($file_dok as $dok) {

              $fileName = $uploadPath."/dokumen_".$dok->baseName ."_". $acak ."_" .$i. "." .$dok->extension;

              $dok->saveAs($fileName);

              $tmp .= "/dokumen_".$dok->baseName ."_". $acak ."_" .$i. "." .$dok->extension."**";

              $i++;
            }

            $tmp = rtrim($tmp,'**');
            $model->file = $tmp;

            $file_gambar = UploadedFile::getInstances($model, 'photoApproves');
            $uploadPath = Yii::getAlias('@backend')."/web/template";
            $acak = substr( md5(time()) , 0, 10);
            $i = 0;
            $tmp = '';
            foreach ($file_gambar as $gambar) {

              $fileName = $uploadPath."/foto_".$gambar->baseName ."_". $acak ."_" .$i. "." .$gambar->extension;

              $gambar->saveAs($fileName);

              $tmp .= "/foto_".$gambar->baseName ."_". $acak ."_" .$i. "." .$gambar->extension."**";

              $i++;
            }

            $tmp = rtrim($tmp,'**');
            $model->photo = $tmp;

            //pengurangan dan penambahan realisasi dana
            if ($modelBudget->budget_value_sum == $modelBudget->budget_value_dp) {
              //noaction
            }elseif ($modelBudget->budget_value_sum > $modelBudget->budget_value_dp) {
              $rangeBudget = $modelBudget->budget_value_sum-$modelBudget->budget_value_dp;
              $baru->department_budget_value = $baru->department_budget_value+$rangeBudget;
            } else {
              $rangeBudget = $modelBudget->budget_value_dp-$modelBudget->budget_value_sum;
              $baru->department_budget_value = $baru->department_budget_value-$rangeBudget;
            }

            $baru->save(false);
            $modelBudget->save(false);

            $model->responsibility_value = 1;
            $model->activity_id = $activity->id ;
            $model->save(false);

            Yii::$app->getSession()->setFlash('success', 'Buat Data Pertanggungjawaban Berhasil');
            return $this->redirect(['department-activity-responsibility/index']);
        }

        return $this->render('create', [
            'model' => $model,
            'modelBudget' => $modelBudget,
            'baru' => $baru,
        ]);
    }

    /**
     * Updates an existing ActivityDaily model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = ActivityDailyResponsibility::find()->where(['activity_id'=>$id])->one();
        $activity = ActivityDaily::find()->where(['id'=>$id])->one();
        $modelBudget = ActivityDailyBudgetDepart::find()->where(['activity_id'=>$activity->id])->one();
        $baru = DepartmentBudget::find()->where(['id'=>$modelBudget->department_budget_id])->one();
        $oldDana = $modelBudget->budget_value_dp;

        $oldfiles = explode("**", $model->file);
        $oldPhotos = explode("**", $model->photo);
        if ($model->load(Yii::$app->request->post())&&$modelBudget->load(Yii::$app->request->post())) {

                $file_dok = UploadedFile::getInstances($model, 'fileApproves');
                $file_gambar = UploadedFile::getInstances($model, 'photoApproves');
                $uploadPath = Yii::getAlias('@backend')."/web/template";


            if ($file_dok || $file_gambar) {

                if ($file_dok) {
                  foreach ($oldfiles as $key => $oldfile) {
                    unlink($uploadPath.$oldfile);
                  }                $uploadPath = Yii::getAlias('@backend')."/web/template";
                $acak = substr( md5(time()) , 0, 10);
                $i = 0;
                $tmp = '';
                foreach ($file_dok as $dok) {

                  $fileName = $uploadPath."/dokumen_".$dok->baseName ."_". $acak ."_" .$i. "." .$dok->extension;

                  $dok->saveAs($fileName);

                  $tmp .= "/dokumen_".$dok->baseName ."_". $acak ."_" .$i. "." .$dok->extension."**";

                  $i++;
                }

                $tmp = rtrim($tmp,'**');
                $model->file = $tmp;
                }

                if ($file_gambar) {
                  foreach ($oldPhotos as $key => $oldPhoto) {
                    unlink($uploadPath.$oldPhoto);
                  }                $uploadPath = Yii::getAlias('@backend')."/web/template";
                $acak = substr( md5(time()) , 0, 10);
                $i = 0;
                $tmp = '';
                foreach ($file_gambar as $gambar) {

                  $fileName = $uploadPath."/foto_".$gambar->baseName ."_". $acak ."_" .$i. "." .$gambar->extension;

                  $gambar->saveAs($fileName);

                  $tmp .= "/foto_".$gambar->baseName ."_". $acak ."_" .$i. "." .$gambar->extension."**";

                  $i++;
                }

                $tmp = rtrim($tmp,'**');
                $model->photo = $tmp;
                }

            }

                //pengurangan dan penambahan realisasi dana
                if ($oldDana == $modelBudget->budget_value_dp) {
                    //noaction
                }elseif ($modelBudget->budget_value_sum == $modelBudget->budget_value_dp) {
                    if ($oldDana > $modelBudget->budget_value_dp) {
                      $rangeBudget = $oldDana-$modelBudget->budget_value_dp;
                      $baru->section_budget_value = $baru->section_budget_value+$rangeBudget;
                    } else {
                      $rangeBudget = $modelBudget->budget_value_dp-$oldDana;
                      $baru->section_budget_value = $baru->section_budget_value-$rangeBudget;
                    }
                } elseif ($modelBudget->budget_value_sum > $modelBudget->budget_value_dp) {
                    $rangeBudget = $modelBudget->budget_value_sum - $modelBudget->budget_value_dp;
                    $baru->department_budget_value = $baru->department_budget_value-$rangeBudget;
                } else {
                    $rangeBudget = $modelBudget->budget_value_dp-$modelBudget->budget_value_sum;
                    $baru->department_budget_value = $baru->department_budget_value+$rangeBudget;
                }

                $baru->save(false);
                $modelBudget->save(false);
                
                $model->responsibility_value = 1;
                $model->save(false);
                Yii::$app->getSession()->setFlash('success', 'Update Data Pertanggungjawaban Berhasil');
                return $this->redirect(['department-activity-responsibility/index']);

        }

        return $this->render('update', [
            'model' => $model,
            'baru' => $baru,
            'modelBudget' => $modelBudget,
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
        $model = ActivityDailyResponsibility::find()->where(['activity_id'=>$id])->one();
        $uploadPath = Yii::getAlias('@backend')."/web/template";
        $oldfiles = explode("**", $model->file);
        $oldPhotos = explode("**", $model->photo);

        foreach ($oldfiles as $key => $oldfile) {
          unlink($uploadPath.$oldfile);
        }

        foreach ($oldPhotos as $key => $oldPhoto) {
          unlink($uploadPath.$oldPhoto);
        }
        Yii::$app->getSession()->setFlash('success', 'Hapus Data Pertanggungjawaban Berhasil');
        $model->delete();
        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionReport($id) {

        $model = ActivityDaily::find()->where(['id'=>$id])->one();
        $budget = ActivityDailyBudgetDepart::find()->where(['activity_id'=>$model->id])->one();
        $baru = DepartmentBudget::find()->where(['id'=>$budget->department_budget_id])->one();
        $sekre = Department::find()->where(['id'=>$baru->department_id])->one();
        $departID = Section::find()->where(['id_depart'=>$sekre->id])->one();
        $departName = Department::find()->where(['id'=>$departID->id_depart])->one();
        $sumber = Budget::find()->where(['id'=>$baru->department_budget_id])->one();
        $lpj = ActivityDailyResponsibility::find()->where(['activity_id'=>$model->id])->one();

        $content = $this->renderPartial('view_pdf',[
            'model'=>$model,
            'budget'=>$budget,
            'baru'=>$baru,
            'sumber'=>$sumber,
            'sekre'=>$sekre,
            'departName'=>$departName,
            'lpj'=>$lpj
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
            // 'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
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
