<?php

namespace backend\controllers;

use Yii;
use common\models\ActivityDaily;
use common\models\ActivityResponsibility;
use common\models\ActivityDailyResponsibility;
use common\models\ActivityDailyBudgetSecretariat;
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
        if (Yii::$app->request->post()) {
            $idSekreBudget = 0;
            $post = Yii::$app->request->post();
            if ($post['jenis_sdm_source']=='4') {
                $data = SecretariatBudget::findOne($post['source_sdm']);
                // $valueNow = $data->secretariat_budget_value+(float)$post['source_value'];
                $data->secretariat_budget_value=$data->secretariat_budget_value-(float)$post['money_budget'];
                $data->save();
                // $valueDP = (float)$post['source_value'];
                $idSekreBudget = $data->id;
            }

            $daily = new ActivityDaily();
            $daily->finance_status = 0;
            $daily->department_status = 0;
            $daily->chief_status = 0;
            $daily->title = $post['judul'];
            $daily->description = $post['description'];
            $daily->role = 4;
            $daily->date_start = $post['from_date'];
            $daily->date_end = $post['to_date'];
            $daily->done = 0;
            $save = $daily->save(false);

            if ($save) {
                
                $sekreBudget = new ActivityDailyBudgetSecretariat();
                $sekreBudget->secretariat_budget_id = $idSekreBudget;
                $sekreBudget->budget_value_dp = $post['money_budget'];
                $sekreBudget->budget_value_sum = $post['source_value'];
                $sekreBudget->activity_id = $daily->id; 
                $sekreBudget->save(false);      
            }

            Yii::$app->getSession()->setFlash('success', 'Buat Data Kegiatan Berhasil');
            return $this->redirect(['index']);

        }
        return $this->render('_form');
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
        $model = ActivityDaily::find()->where(['id'=>$id])->one();
        $budget = ActivityDailyBudgetSecretariat::find()->where(['activity_id'=>$model])->one();
        $awal = ActivityDailyBudgetSecretariat::find()->where(['secretariat_budget_id'=>$budget])->one();
        $baru = SecretariatBudget::find()->where(['id'=>$awal])->one();
        $range = $model->date_start.' to '.$model->date_end;
        $range_start = $model->date_start;
        $range_end = $model->date_end;

        if ($model->load(Yii::$app->request->post())) {
            $post = Yii::$app->request->post();
            $model->title = $model->title;
            $model->description = $model->description;
            // $model->date_start = $model->date_start;
            // $model->date_end = $model->date_end;
            $model->date_start = $post['from_date'];
            $model->date_end = $post['to_date'];
            $save = $model->save(false);

            if ($save && $budget->load(Yii::$app->request->post())) {
                
                // var_dump($budget);die();
                $dp = $budget->budget_value_dp;
                $total = $budget->budget_value_sum;
                $modal = $baru->secretariat_budget_value;

                if ($dp > $modal) {
                    Yii::$app->getSession()->setFlash('danger', 'Uang Muka Tidak Boleh Lebih Dari Nilai Anggaran Saat Ini');
                    return $this->redirect(Yii::$app->request->referrer);
                }

                if ($dp > $total) {
                        Yii::$app->getSession()->setFlash('danger', 'Uang Muka Tidak Boleh Lebih Dari Total Nilai Anggaran Yang Diajukan');
                        return $this->redirect(Yii::$app->request->referrer);
                }

                $budget->budget_value_dp = $budget->budget_value_dp;
                $budget->budget_value_sum = $budget->budget_value_sum;
                $budget->save(false);      
            }

            Yii::$app->getSession()->setFlash('success', 'Update Data Kegiatan Rutin Berhasil');
            return $this->redirect(['index']);

        }

        return $this->render('update', [
            'model' => $model,
            'budget' => $budget,
            'baru' => $baru,
            'range' => $range,
            'range_start' => $range_start,
            'range_end' => $range_end,
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
        $activity = ActivityDaily::find()->where(['id'=>$id])->one();
        $approve = ActivityDailyResponsibility::find()->where(['activity_id'=>$activity])->one();
        $sekreBudget = ActivityDailyBudgetSecretariat::find()->where(['activity_id'=>$activity])->one();
        if ($approve) {
            $approve->delete();
            $activity->delete();
        }
        if ($sekreBudget) {
            $sekreBudget->delete();
            $activity->delete();
        }
        $activity->delete();

        Yii::$app->getSession()->setFlash('success', 'Hapus Data Kegiatan Berhasil');
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
                $budgetSekre = ActivityDailyBudgetSecretariat::find()->where(['secretariat_budget_id'=>$datas])->one();
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

    public function actionNilaiAnggaranUpdate(){
        $post = Yii::$app->request->post();
        if ($post['tipe']=='4') {
            $data = SecretariatBudget::findOne($post['kode']);
            if ($data) {
                // $ativity = ActivityDaily::find()->where(['id'=>'id'])->one();
                $budgetSekre = ActivityDailyBudgetSecretariat::find()->where(['secretariat_budget_id'=>$data])->one();
                // $hasilDP = $budgetSekre->budget_value_sum - $budgetSekre->budget_value_dp;
                // $hasil =  ($hasilDP + $data->secretariat_budget_value) / 2;
                $datas['message']= "
                 <div class='col-sm-12'>
                    <div class='form-group'>
                        <label class='col-sm-4'>Nilai Anggaran Saat Ini</label>
                        <div class='col-sm-8'>
                            ".$hasil."
                        </div>
                    </div>
                </div>
                <br>
                <br>
                ";
                $datas['max']=$hasil;
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
