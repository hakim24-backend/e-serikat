<?php

namespace backend\controllers;

use Yii;
use common\models\ActivityDaily;
use common\models\ActivityDailyResponsibility;
use common\models\ActivityDailyBudgetChief;
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

/**
 * ChiefActivityDailyResponsibilityController implements the CRUD actions for ActivityDaily model.
 */
class ChiefActivityDailyResponsibilityController extends Controller
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
        $dataProvider = new ActiveDataProvider([
            'query' => ActivityDaily::find()->where(['role'=>6]),
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
        if ($model->load(Yii::$app->request->post())) {

            $file_dok = UploadedFile::getInstance($model, 'fileApprove');
            $uploadPath = Yii::getAlias('@backend')."/web/template";
            $acak = substr( md5(time()) , 0, 10);
            $fileName = $uploadPath."/dokumen_".$file_dok->baseName ."_". $acak.".".$file_dok->extension;
            $file_dok->saveAs($fileName);

            $file_gambar = UploadedFile::getInstance($model, 'photoApprove');
            $uploadPath = Yii::getAlias('@backend')."/web/template";
            $acak = substr( md5(time()) , 0, 10);
            $fotoName = $uploadPath."/foto_".$file_gambar->baseName ."_". $acak.".".$file_gambar->extension;
            // var_dump($fotoName);die;
            $file_gambar->saveAs($fotoName);


            $model->description = $model->description;
            $model->responsibility_value = 0;
            $model->file = "/dokumen_".$file_dok->baseName ."_". $acak.".".$file_dok->extension;
            $model->photo = "/foto_".$file_gambar->baseName ."_". $acak.".".$file_gambar->extension;
            $model->activity_id = $activity->id ;
            $model->save(false);
            Yii::$app->getSession()->setFlash('success', 'Buat Data Pertanggungjawaban Berhasil');
            return $this->redirect(['index']);
        }

        return $this->render('create', [
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
    public function actionUpdate($id)
    {
        $model = ActivityDailyResponsibility::find()->where(['activity_id'=>$id])->one();
        $oldfile = $model->file;
        $oldPhoto = $model->photo;
        if ($model->load(Yii::$app->request->post())) {

                $file_dok = UploadedFile::getInstance($model, 'fileApprove');
                $file_gambar = UploadedFile::getInstance($model, 'photoApprove');
                $uploadPath = Yii::getAlias('@backend')."/web/template";


            if ($file_dok || $file_gambar) {

                if ($file_dok) {
                unlink($uploadPath.$oldfile);
                $uploadPath = Yii::getAlias('@backend')."/web/template";
                $acak = substr( md5(time()) , 0, 10);
                $fileName = $uploadPath."/dokumen_".$file_dok->baseName ."_". $acak.".".$file_dok->extension;
                $file_dok->saveAs($fileName);

                $model->description = $model->description;
                $model->file = "/dokumen_".$file_dok->baseName ."_". $acak.".".$file_dok->extension;
                }

                if ($file_gambar) {
                unlink($uploadPath.$oldPhoto);
                $uploadPath = Yii::getAlias('@backend')."/web/template";
                $acak = substr( md5(time()) , 0, 10);
                $fotoName = $uploadPath."/foto_".$file_gambar->baseName ."_". $acak.".".$file_gambar->extension;
                $file_gambar->saveAs($fotoName);

                $model->description = $model->description;
                $model->photo = "/foto_".$file_gambar->baseName ."_". $acak.".".$file_gambar->extension;
                }

                $model->save(false);
                Yii::$app->getSession()->setFlash('success', 'Update Data Pertanggungjawaban Berhasil');
                return $this->redirect(['highlight','id'=>$model->activity_id]);


            } else {
                $model->description = $model->description;
                $model->save(false);
                Yii::$app->getSession()->setFlash('success', 'Update Data Pertanggungjawaban Berhasil');
                return $this->redirect(['highlight','id'=>$model->activity_id]);
            }
        }

        return $this->render('update', [
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
     $model = ActivityDailyResponsibility::find()->where(['activity_id'=>$id])->one();
        $uploadPath = Yii::getAlias('@backend')."/web/template";
        $oldfile = $model->file;
        $oldPhoto = $model->photo;
        unlink($uploadPath.$oldfile);
        unlink($uploadPath.$oldPhoto);
        Yii::$app->getSession()->setFlash('success', 'Hapus Data Pertanggungjawaban Berhasil');
        $model->delete();
        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionReport($id) {

        $model = ActivityDaily::find()->where(['id'=>$id])->one();
        $budget = ActivityDailyBudgetChief::find()->where(['activity_id'=>$model])->one();
        $awal = ActivityDailyBudgetChief::find()->where(['chief_budget_id'=>$budget])->one();
        $baru = ChiefBudget::find()->where(['id'=>$awal])->one();
        $sekre = Chief::find()->where(['id'=>$baru])->one();
        // $departID = Section::find()->where(['id_depart'=>$sekre])->one();
        // $departName = Department::find()->where(['id'=>$departID])->one();
        $sumber = Budget::find()->where(['id'=>$baru])->one();

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
        if (($model = ActivityDailyResponsibility::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
