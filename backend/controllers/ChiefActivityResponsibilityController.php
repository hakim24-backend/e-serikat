<?php

namespace backend\controllers;

use Yii;
use common\models\Activity;
use common\models\ActivityResponsibility;
use common\models\ActivityBudgetChief;
use common\models\ChiefBudget;
use common\models\Budget;
use common\models\Chief;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use kartik\mpdf\Pdf;

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
        $dataProvider = new ActiveDataProvider([
            'query' => Activity::find()->where(['role'=>6]),
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
        if ($model->load(Yii::$app->request->post())) {

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

            $model->responsibility_value = 0;
            $model->activity_id = $id ;
            $model->save(false);
            Yii::$app->getSession()->setFlash('success', 'Buat Data Pertanggungjawaban Berhasil');
            return $this->redirect(['department-activity-responsibility/index/']);
        }

        return $this->render('create', [
            'model' => $model,
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
        $oldfiles = explode("**", $model->file);
        $oldPhotos = explode("**", $model->photo);
        if ($model->load(Yii::$app->request->post())) {

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

                $model->save(false);
                Yii::$app->getSession()->setFlash('success', 'Update Data Pertanggungjawaban Berhasil');
                return $this->redirect(['index','id'=>$model->activity_id]);


            } else {
                $model->save(false);
                Yii::$app->getSession()->setFlash('success', 'Update Data Pertanggungjawaban Berhasil');
                return $this->redirect(['index','id'=>$model->activity_id]);
            }
        }
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionReport($id) {

          $model = Activity::find()->where(['id'=>$id])->one();
          $budget = ActivityBudgetChief::find()->where(['activity_id'=>$model])->one();
          $awal = ActivityBudgetChief::find()->where(['chief_budget_id'=>$budget])->one();
          $baru = ChiefBudget::find()->where(['id'=>$awal])->one();
          $sekre = Chief::find()->where(['id'=>$baru])->one();
          $sumber = Budget::find()->where(['id'=>$baru])->one();
          // $departID = Chief::find()->where(['id_chief'=>$sekre])->one();
          // $departName = Chief::find()->where(['id'=>$departID])->one();

        $content = $this->renderPartial('view_pdf',[
            'model'=>$model,
            'budget'=>$budget,
            'baru'=>$baru,
            'sumber'=>$sumber,
            'sekre'=>$sekre,
            // 'departName'=>$departName
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
        $oldfile = $model->file;
        $oldPhoto = $model->photo;
        unlink($uploadPath.$oldfile);
        unlink($uploadPath.$oldPhoto);
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
