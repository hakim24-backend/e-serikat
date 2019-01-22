<?php

namespace backend\controllers;

use Yii;
use common\models\ActivityDaily;
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

/**
 * KegiatanRutinController implements the CRUD actions for ActivityDaily model.
 */
class KegiatanRutinController extends Controller
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
     * Lists all ActivityDaily models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => ActivityDaily::find(),
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
    public function actionCreate()
    {
        $model = new ActivityDaily();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
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
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
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
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
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

    public function actionKodeTujuan($id)
    {
        if ($id=='4') {
            $data = SecretariatBudget::find()->all();
            echo "<option value=0'> Pilih Kode Anggaran </option>";

            if ($data) {
                foreach ($data as $datas) {
                    echo "<option value='".$datas->id."'>".$datas->secretariat_budget_code."</option>";
                }
            }
        }elseif ($id=='6') {
            $data = ChiefBudget::find()->all();
            echo "<option value=0'> Pilih Kode Anggaran </option>";

            if ($data) {
                foreach ($data as $datas) {
                    echo "<option value='".$datas->id."'>".$datas->chief_budget_code."</option>";
                }
            }
        }elseif ($id=='7') {
            $data = DepartmentBudget::find()->all();
            echo "<option value=0'> Pilih Kode Anggaran </option>";

            if ($data) {
                foreach ($data as $datas) {
                    echo "<option value='".$datas->id."'>".$datas->department_budget_code."</option>";
                }
            }
        }elseif ($id=='8') {
            $data = SectionBudget::find()->all();
            echo "<option value=0'> Pilih Kode Anggaran </option>";

            if ($data) {
                foreach ($data as $datas) {
                    echo "<option value='".$datas->id."'>".$datas->section_budget_code."</option>";
                }
            }
        }else{
            echo "<option value=0'> Pilih Kode Anggaran </option>";
        }
    }

    public function actionNilaiAnggaran(){
        $post = Yii::$app->request->post();
        if ($post['tipe']=='4') {
            $data = SecretariatBudget::findOne($post['kode']);
            if ($data) {
                $datas['message']= "
                 <div class='col-sm-12'>
                    <div class='form-group'>
                        <label class='col-sm-4'>Nilai Anggaran Saat Ini</label>
                        <div class='col-sm-8'>
                            ".$data->secretariat_budget_value."
                        </div>
                    </div>
                </div>
                <br>
                <br>
                ";
                $datas['max']=$data->secretariat_budget_value;
            }else{
                $datas['message']= "
                 <div class='col-sm-12'>
                    <div class='form-group'>
                        <label class='col-sm-4'>Nilai Anggaran Saat Ini</label>
                        <div class='col-sm-8'>
                            0
                        </div>
                    </div>
                </div>
                <br>
                <br>
                ";
                $datas['max']=0;
            }
        }elseif ($post['tipe']=='6') {
            $data = ChiefBudget::findOne($post['kode']);
            if ($data) {
                $datas['message']= "
                 <div class='col-sm-12'>
                    <div class='form-group'>
                        <label class='col-sm-4'>Nilai Anggaran Saat Ini</label>
                        <div class='col-sm-8'>
                            ".$data->chief_budget_value."
                        </div>
                    </div>
                </div>
                <br>
                <br>
                ";
                $datas['max']=$data->chief_budget_value;
            }else{
                $datas['message']= "
                 <div class='col-sm-12'>
                    <div class='form-group'>
                        <label class='col-sm-4'>Nilai Anggaran Saat Ini</label>
                        <div class='col-sm-8'>
                            0
                        </div>
                    </div>
                </div>
                <br>
                <br>
                ";
                $datas['max']=0;
            }
        }elseif ($post['tipe']=='7') {
            $data = DepartmentBudget::findOne($post['kode']);
            if ($data) {
                $datas['message']= "
                 <div class='col-sm-12'>
                    <div class='form-group'>
                        <label class='col-sm-4'>Nilai Anggaran Saat Ini</label>
                        <div class='col-sm-8'>
                            ".$data->department_budget_value."
                        </div>
                    </div>
                </div>
                <br>
                <br>
                ";
                $datas['max']=$data->department_budget_value;
            }else{
                $datas['message']= "
                 <div class='col-sm-12'>
                    <div class='form-group'>
                        <label class='col-sm-4'>Nilai Anggaran Saat Ini</label>
                        <div class='col-sm-8'>
                            0
                        </div>
                    </div>
                </div>
                <br>
                <br>
                ";
                $datas['max']=0;
            }
        }elseif ($post['tipe']=='8') {
            $data = SectionBudget::findOne($post['kode']);
            if ($data) {
                $datas['message']= "
                 <div class='col-sm-12'>
                    <div class='form-group'>
                        <label class='col-sm-4'>Nilai Anggaran Saat Ini</label>
                        <div class='col-sm-8'>
                            ".$data->section_budget_value."
                        </div>
                    </div>
                </div>
                <br>
                <br>
                ";
                $datas['max']=$data->section_budget_value;
            }else{
                $datas['message']= "
                 <div class='col-sm-12'>
                    <div class='form-group'>
                        <label class='col-sm-4'>Nilai Anggaran Saat Ini</label>
                        <div class='col-sm-8'>
                            0
                        </div>
                    </div>
                </div>
                <br>
                <br>
                ";
                $datas['max']=0;
            }
        }else{
            $datas['message']= "
             <div class='col-sm-12'>
                <div class='form-group'>
                    <label class='col-sm-4'>Nilai Anggaran Saat Ini</label>
                    <div class='col-sm-8'>
                        0
                    </div>
                </div>
            </div>
            <br>
            <br>
            ";
            $datas['max']=0;
        }
        echo json_encode($datas);
    }
}
