<?php

namespace backend\controllers;

use Yii;
use common\models\ActivityResponsibility;
use common\models\Approve;
use common\models\Activity;
use common\models\User;
use common\models\ActivityBudgetSection;
use common\models\ActivityBudgetSecretariat;
use common\models\SectionBudget;
use common\models\SecretariatBudget;
use common\models\Department;
use common\models\Section;
use common\models\Secretariat;
use common\models\Budget;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DepartmentActivityResponsibilityController implements the CRUD actions for ActivityResponsibility model.
 */
class DepartmentActivityResponsibilityController extends Controller
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
     * Lists all ActivityResponsibility models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Activity::find()->where(['role'=>7]),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ActivityResponsibility model.
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
     * Creates a new ActivityResponsibility model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ActivityResponsibility();
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


            $model->responsibility_value = 0;
            $model->file = "/dokumen_".$file_dok->baseName ."_". $acak.".".$file_dok->extension;
            $model->photo = "/foto_".$file_gambar->baseName ."_". $acak.".".$file_gambar->extension;
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
     * Updates an existing ActivityResponsibility model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = ActivityResponsibility::find()->where(['activity_id'=>$id])->one();
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
                return $this->redirect(['index','id'=>$model->activity_id]);


            } else {
                $model->description = $model->description;
                $model->save(false);
                Yii::$app->getSession()->setFlash('success', 'Update Data Pertanggungjawaban Berhasil');
                return $this->redirect(['index','id'=>$model->activity_id]);
            }
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
