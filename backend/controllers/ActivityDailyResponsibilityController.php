<?php

namespace backend\controllers;

use Yii;
use common\models\ActivityDaily;
use common\models\Budget;
use common\models\Department;
use common\models\Secretariat;
use common\models\Section;
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
use yii\web\UploadedFile;

/**
 * ApproveController implements the CRUD actions for Approve model.
 */
class ActivityDailyResponsibilityController extends Controller
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
                        'actions' => ['logout','index','download','view','create','update','delete','report'],
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
     * Lists all Approve models.
     * @return mixed
     */
    public function actionIndex()
    {
        $role = Yii::$app->user->identity->role;
        if($role == 4){
          $dataProvider = new ActiveDataProvider([
            'query' => ActivityDaily::find()->where(['role'=>4])->Andwhere(['finance_status'=> 1])->andWhere(['department_status'=> 1])->andWhere(['chief_status'=> 1]),
          ]);
        }elseif ($role == 8) {
          $atasan = Yii::$app->user->identity->section->id_depart;
          $dataProvider = new ActiveDataProvider([
            'query' => ActivityDaily::find()
            ->joinWith('activityDailyBudgetSections')
            ->joinWith('activityDailyBudgetSections.sectionBudget')
            ->joinWith('activityDailyBudgetSections.sectionBudget.section')
            ->where(['activity_daily.role'=>8])
            ->andWhere(['section.id_depart'=>$atasan])
            ->Andwhere(['activity_daily.finance_status'=> 1])
            ->andWhere(['activity_daily.department_status'=> 1])
            ->andWhere(['activity_daily.chief_status'=> 1]),
          ]);

        }elseif ($role == 2 || $role == 3) {
          $dataProvider = new ActiveDataProvider([
            'query' => ActivityDaily::find()->where(['finance_status'=> 1])->andWhere(['department_status'=> 1])->andWhere(['chief_status'=> 1]),
          ]);
        }

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionDownload($id)
    {
        $download = ActivityDailyResponsibility::findOne($id);
        $path=Yii::getAlias('@backend').'/web/template'.$download->file;

        if (file_exists($path)) {
            return Yii::$app->response->sendFile($path);
        }
    }

    /**
     * Displays a single Approve model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => ActivityDailyResponsibility::find()->where(['id'=>$id])->one(),
        ]);
    }

    /**
     * Creates a new Approve model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id)
    {

        $role = Yii::$app->user->identity->role;
        $activity = ActivityDaily::find()->where(['id'=>$id])->one();
        $model = new ActivityDailyResponsibility();

        if($role == 8){
          $modelBudget = ActivityDailyBudgetSection::find()->where(['activity_id'=>$activity->id])->one();
          $awal = ActivityDailyBudgetSection::find()->where(['section_budget_id'=>$modelBudget->section_budget_id])->one();
          $baru = SectionBudget::find()->where(['id'=>$awal->section_budget_id])->one();

        }else if($role == 4){
          $modelBudget = ActivityDailyBudgetSecretariat::find()->where(['activity_id'=>$activity->id])->one();
          $awal = ActivityDailyBudgetSecretariat::find()->where(['secretariat_budget_id'=>$modelBudget->secretariat_budget_id])->one();
          $baru = SecretariatBudget::find()->where(['id'=>$awal->secretariat_budget_id])->one();

        }

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

            $model->activity_id = $activity->id ;

            if($role == 8){
              $model->responsibility_value = 0;

              //pengurangan dan penambahan realisasi dana
              
              // if ($modelBudget->budget_value_sum == $modelBudget->budget_value_dp) {
              //   //noaction
              // }elseif ($modelBudget->budget_value_sum > $modelBudget->budget_value_dp) {
              //   $rangeBudget = $modelBudget->budget_value_sum-$modelBudget->budget_value_dp;
              //   $baru->section_budget_value = $baru->section_budget_value+$rangeBudget;
              // } else {
              //   $rangeBudget = $modelBudget->budget_value_dp-$modelBudget->budget_value_sum;
              //   $baru->section_budget_value = $baru->section_budget_value-$rangeBudget;
              // }

              $baru->save(false);
              $modelBudget->save(false);

            }else if($role == 4){
              $model->responsibility_value = 2;
              $modelBudget->save(false);
            }

            $model->save(false);
            Yii::$app->getSession()->setFlash('success', 'Buat Data Pertanggungjawaban Berhasil');
            return $this->redirect(['activity-responsibility/index']);
        }

        return $this->render('create', [
            'model' => $model,
            'modelBudget' => $modelBudget,
            'baru' => $baru,
        ]);
    }

    /**
     * Updates an existing Approve model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $role = Yii::$app->user->identity->role;

        $model = ActivityDailyResponsibility::find()->where(['activity_id'=>$id])->one();
        $activity = ActivityDaily::find()->where(['id'=>$id])->one();

        if($role == 8){

          $modelBudget = ActivityDailyBudgetSection::find()->where(['activity_id'=>$activity->id])->one();
          $baru = SectionBudget::find()->where(['id'=>$modelBudget->section_budget_id])->one();
          $oldDana = $modelBudget->budget_value_dp;
        }else if($role == 4){
          $modelBudget = ActivityDailyBudgetSecretariat::find()->where(['activity_id'=>$activity->id])->one();
          $baru = SecretariatBudget::find()->where(['id'=>$modelBudget->secretariat_budget_id])->one();
          $oldDana = $modelBudget->budget_value_dp;

        }
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
            if($role == 8){
              $model->responsibility_value = 0;

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
                  $baru->section_budget_value = $baru->section_budget_value-$rangeBudget;
              } else {
                  $rangeBudget = $modelBudget->budget_value_dp-$modelBudget->budget_value_sum;
                  $baru->section_budget_value = $baru->section_budget_value+$rangeBudget;
              }

              $baru->save(false);
              $modelBudget->save(false);

            }else if($role == 4){
              $model->responsibility_value = 2;
              $modelBudget->save(false);
            }

            $model->save(false);
            Yii::$app->getSession()->setFlash('success', 'Update Data Pertanggungjawaban Berhasil');
            return $this->redirect(['activity-responsibility/index']);

        }
        return $this->render('update', [
            'model' => $model,
            'modelBudget' => $modelBudget,
            'baru' => $baru,
        ]);
    }

    /**
     * Deletes an existing Approve model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = ActivityDailyResponsibility::find()->where(['id'=>$id])->one();
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
    $role = Yii::$app->user->identity->roleName();

    $departName = array();

    if ($role == "Sekretariat") {
        $model = ActivityDaily::find()->where(['id'=>$id])->one();
        $budget = ActivityDailyBudgetSecretariat::find()->where(['activity_id'=>$model->id])->one();
        $baru = SecretariatBudget::find()->where(['id'=>$budget->secretariat_budget_id])->one();
        $sekre = Secretariat::find()->where(['id'=>$baru->secretariat_id])->one();
        $sumber = Budget::find()->where(['id'=>$baru->secretariat_budget_id])->one();
        $lpj = ActivityDailyResponsibility::find()->where(['activity_id'=>$model->id])->one();
    } else if ($role == "Seksi") {
        $model = ActivityDaily::find()->where(['id'=>$id])->one();
        $budget = ActivityDailyBudgetSection::find()->where(['activity_id'=>$model->id])->one();
        $baru = SectionBudget::find()->where(['id'=>$budget->section_budget_id])->one();
        $sekre = Section::find()->where(['id'=>$baru->section_id])->one();
        $sumber = Budget::find()->where(['id'=>$baru->section_budget_id])->one();
        $departName = Department::find()->where(['id'=>$sekre->id_depart])->one();
        $lpj = ActivityDailyResponsibility::find()->where(['activity_id'=>$model->id])->one();
    }else if ($role=="Sekertaris Umum" || $role=="Ketua Umum") {
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
    }

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
     * Finds the Approve model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Approve the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Approve::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
