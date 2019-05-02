<?php

namespace backend\controllers;

use Yii;
use common\models\Activity;
use common\models\ActivityDaily;
use common\models\ActivityResponsibility;
use common\models\ActivityBudgetChief;
use common\models\ChiefBudget;
use common\models\Budget;
use common\models\Chief;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\UploadedFile;
use kartik\mpdf\Pdf;
use yii\data\ArrayDataProvider;

/**
 * ChiefActivityResponsibilityController implements the CRUD actions for Activity model.
 */
class ChiefActivityResponsibilityController extends Controller
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
                        'actions' => ['logout','index','view','create','update','report','delete'],
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
        $id_chief = Yii::$app->user->identity->chief->id;

        $dataA = Activity::find()
                    ->where(['role'=>6])
                    ->andWhere(['chief_code_id'=>$id_chief])
                    ->andWhere(['finance_status'=> 1])
                    ->andWhere(['chief_status'=>1])
                    ->andWhere(['department_status'=>1])
                    ->asArray()->all();

                    $typeA = array(
                      'tipe' => 'kegiatan',
                    );
                    //
                    foreach ($dataA as $key => $data) {
                      array_splice($dataA[$key], 0, 0 , $typeA);
                    }

          $dataB = ActivityDaily::find()
                    ->where(['role'=>6])
                    ->andWhere(['chief_code_id'=>$id_chief])
                    ->andWhere(['finance_status'=> 1])
                    ->andWhere(['chief_status'=>1])
                    ->andWhere(['department_status'=>1])
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
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Activity model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id)
    {
        $model = new ActivityResponsibility();
        $activity = Activity::find()->where(['id'=>$id])->one();
        $modelBudget = ActivityBudgetChief::find()->where(['activity_id'=>$activity->id])->one();
        $awal = ActivityBudgetChief::find()->where(['chief_budget_id'=>$modelBudget])->one();
        $baru = ChiefBudget::find()->where(['id'=>$modelBudget->chief_budget_id])->one();

        if ($model->load(Yii::$app->request->post())&& $modelBudget->load(Yii::$app->request->post())) {

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
            
            // if ($modelBudget->budget_value_sum == $modelBudget->budget_value_dp) {
            //   //noaction
            // }elseif ($modelBudget->budget_value_sum > $modelBudget->budget_value_dp) {
            //   $rangeBudget = $modelBudget->budget_value_sum-$modelBudget->budget_value_dp;
            //   $baru->chief_budget_value = $baru->chief_budget_value+$rangeBudget;
            // } else {
            //   $rangeBudget = $modelBudget->budget_value_dp-$modelBudget->budget_value_sum;
            //   $baru->chief_budget_value = $baru->chief_budget_value-$rangeBudget;
            // }

            $baru->save(false);
            $modelBudget->save(false);

            $model->responsibility_value = 2;
            $model->activity_id = $id ;
            $model->save(false);

            Yii::$app->getSession()->setFlash('success', 'Buat Data Pertanggungjawaban Berhasil');
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
            'modelBudget' => $modelBudget,
            'baru' => $baru,
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

        $model = ActivityResponsibility::find()->where(['activity_id'=>$id])->one();
        $activity = Activity::find()->where(['id'=>$id])->one();
        $modelBudget = ActivityBudgetChief::find()->where(['activity_id'=>$activity->id])->one();
        $baru = ChiefBudget::find()->where(['id'=>$modelBudget->chief_budget_id])->one();
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
                  }

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

                }

                if ($file_gambar) {

                  foreach ($oldPhotos as $key => $oldPhoto) {
                    unlink($uploadPath.$oldPhoto);
                  }

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
                }

            }

                //pengurangan dan penambahan realisasi dana
                if ($oldDana == $modelBudget->budget_value_dp) {
                    //noaction
                }elseif ($modelBudget->budget_value_sum == $modelBudget->budget_value_dp) {
                    if ($oldDana > $modelBudget->budget_value_dp) {
                      $rangeBudget = $oldDana-$modelBudget->budget_value_dp;
                      $baru->chief_budget_value = $baru->chief_budget_value+$rangeBudget;
                    } else {
                      $rangeBudget = $modelBudget->budget_value_dp-$oldDana;
                      $baru->chief_budget_value = $baru->chief_budget_value-$rangeBudget;
                    }
                } elseif ($modelBudget->budget_value_sum > $modelBudget->budget_value_dp) {
                    $rangeBudget = $modelBudget->budget_value_sum - $modelBudget->budget_value_dp;
                    $baru->chief_budget_value = $baru->chief_budget_value+$rangeBudget;
                } else {
                    $rangeBudget = $modelBudget->budget_value_dp-$modelBudget->budget_value_sum;
                    $baru->chief_budget_value = $baru->chief_budget_value-$rangeBudget;
                }

                $baru->save(false);
                $modelBudget->save(false);

                $model->responsibility_value = 2;
                $model->save(false);

                Yii::$app->getSession()->setFlash('success', 'Update Data Pertanggungjawaban Berhasil');
                return $this->redirect(['index']);

        }
        return $this->render('update', [
            'model' => $model,
            'modelBudget' => $modelBudget,
            'baru' => $baru,
        ]);
    }

    public function actionReport($id) {

          $model = Activity::find()->where(['id'=>$id])->one();
          $budget = ActivityBudgetChief::find()->where(['activity_id'=>$model->id])->one();
          $baru = ChiefBudget::find()->where(['id'=>$budget->chief_budget_id])->one();
          $sekre = Chief::find()->where(['id'=>$baru->chief_id])->one();
          $sumber = Budget::find()->where(['id'=>$baru->chief_budget_id])->one();
          $lpj = ActivityResponsibility::find()->where(['activity_id'=>$model->id])->one();

        $content = $this->renderPartial('view_pdf',[
            'model'=>$model,
            'budget'=>$budget,
            'baru'=>$baru,
            'sumber'=>$sumber,
            'sekre'=>$sekre,
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
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',
            // any css to be embedded if required
            'cssInline' => '.kv-heading-1{font-size:18px}',
             // set mPDF properties on the fly
            'options' => ['title' => 'Krajee Report Title'],
             // call mPDF methods on the fly
            'methods' => [
                'SetFooter'=>['{PAGENO}'],
            ]
        ]);

    // return the pdf output as per the destination setting
    return $pdf->render();
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
        $model = ActivityResponsibility::find()->where(['activity_id'=>$id])->one();
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

    /**
     * Finds the Activity model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Activity the loaded model
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
