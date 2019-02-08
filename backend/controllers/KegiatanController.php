<?php

namespace backend\controllers;

use Yii;
use common\models\Model;
use common\models\Activity;
use common\models\ActivityMainMember;
use common\models\ActivitySection;
use common\models\ActivitySectionMember;
use common\models\ActivityResponsibility;
use common\models\ActivityBudgetSecretariat;
use common\models\ActivityBudgetSection;
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
use yii\helpers\ArrayHelper;
/**
 * KegiatanController implements the CRUD actions for Activity model.
 */
class KegiatanController extends Controller
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
        $role = Yii::$app->user->identity->role;
        if($role != 1 && $role != 5){
          $dataProvider = new ActiveDataProvider([
            'query' => Activity::find()->where(['role'=>$role]),
          ]);
        }else{
          $dataProvider = new ActiveDataProvider([
            'query' => Activity::find(),
          ]);
        }

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

     public function actionCreate() {

         $model = new Activity();
         $modelsSection = [new ActivitySection()];
         $modelsMember[] = [new ActivitySectionMember];
         $role = Yii::$app->user->identity->role;

         if ($model->load(Yii::$app->request->post())) {

             $post = Yii::$app->request->post();
             $model->role = Yii::$app->user->identity->role;
             $model->finance_status = 0;
             $model->department_status = 0;
             $model->chief_status = 0;
             $model->done = 0;
             $model->date_start = $post['from_date'];
             $model->date_end = $post['to_date'];

             if ($role == 4) {
                $data = SecretariatBudget::findOne($post['source_sdm']); 
                if ($post['source_value'] > $data->secretariat_budget_value ) {
                 Yii::$app->getSession()->setFlash('danger', 'Dana Yang Diajukan Melebihi Anggaran Saat Ini');
                 return $this->redirect(Yii::$app->request->referrer);
                }  
             } elseif ($role == 8) {
                $data = SectionBudget::findOne($post['source_sdm']);
                if ($post['source_value'] > $data->section_budget_value ) {
                 Yii::$app->getSession()->setFlash('danger', 'Dana Yang Diajukan Melebihi Anggaran Saat Ini');
                 return $this->redirect(Yii::$app->request->referrer);
                }
             }

             if ($post['money_budget'] > $post['source_value']) {
                 Yii::$app->getSession()->setFlash('danger', 'Tidak Bisa Melebihi Anggaran Dana Yang Diajukan');
                 return $this->redirect(Yii::$app->request->referrer);
             }

             if ($post['jenis_sdm_source']=='4') {
                 $data = SecretariatBudget::findOne($post['source_sdm']);
                 $data->secretariat_budget_value=$data->secretariat_budget_value-(float)$post['money_budget'];
                 $data->save();
                 $idSekreBudget = $data->id;
             } else if ($post['jenis_sdm_source']=='8') {
                 $data = SectionBudget::findOne($post['source_sdm']);
                 $data->section_budget_value=$data->section_budget_value-(float)$post['money_budget'];
                 $data->save();
                 $idSectionBudget = $data->id;
             }



             // get ActivitySection data from POST
             $modelsSection = Model::createMultiple(ActivitySection::classname());
             Model::loadMultiple($modelsSection, Yii::$app->request->post());

             // get ActivitySectionMember data from POST
             $loadsData =  Yii::$app->request->post();
             for ($i=0; $i<count($modelsSection); $i++) {
                 $loadsData['ActivitySectionMember'] =  Yii::$app->request->post()['ActivitySectionMember'][$i];
                 $modelsMember[$i] = Model::createMultiple(ActivitySectionMember::classname(),[] ,$loadsData);
                 Model::loadMultiple($modelsMember[$i], $loadsData);
             }

             // validate all models - see example online for ajax validation
             // https://github.com/wbraganca/yii2-dynamicform
             $valid = $model->validate();

             // save deposit data
             if ($valid) {
                 if ($this->saveDeposit($model,$modelsSection,$modelsMember)) {

                   if($post){
                     if($post['ketua']){
                       $modelsMain = new ActivityMainMember;
                       $modelsMain->name_committee = "Ketua";
                       $modelsMain->name_member = $post['ketua'];
                       $modelsMain->activity_id = $model->id;
                       // var_dump("test");die;

                       $modelsMain->save();
                     }
                     if($post['wakil']){
                       $modelsMain = new ActivityMainMember;
                       $modelsMain->name_committee = "Wakil";
                       $modelsMain->name_member = $post['wakil'];
                       $modelsMain->activity_id = $model->id;
                       // var_dump("test");die;

                       $modelsMain->save();
                     }
                     if($post['sekretaris']){
                       $modelsMain = new ActivityMainMember;
                       $modelsMain->name_committee = "Sekretaris";
                       $modelsMain->name_member = $post['sekretaris'];
                       $modelsMain->activity_id = $model->id;
                       // var_dump("test");die;

                       $modelsMain->save();
                     }
                     if($post['bendahara']){
                       $modelsMain = new ActivityMainMember;
                       $modelsMain->name_committee = "Bendahara";
                       $modelsMain->name_member = $post['bendahara'];
                       $modelsMain->activity_id = $model->id;
                       // var_dump("test");die;

                       $modelsMain->save();
                     }
                   }


                       if ($role == 4) {
                           $sekreBudget = new ActivityBudgetSecretariat();
                           $sekreBudget->secretariat_budget_id = $idSekreBudget;
                           $sekreBudget->budget_value_dp = $post['money_budget'];
                           $sekreBudget->budget_value_sum = $post['source_value'];

                           $sekreBudget->activity_id = $model->id;
                           $sekreBudget->save(false);
                       } elseif ($role == 8) {
                           $sectionBudget = new ActivityBudgetSection();
                           $sectionBudget->section_budget_id = $idSectionBudget;
                           $sectionBudget->budget_value_dp = $post['money_budget'];
                           $sectionBudget->budget_value_sum = $post['source_value'];

                           $sectionBudget->activity_id = $model->id;
                           $sectionBudget->save(false);
                       }
                       return $this->redirect('index');
                 }
             }
         }

         return $this->render('_form', [
             'model' => $model,
             'modelsSection'  => (empty($modelsSection)) ? [new ActivitySection] : $modelsSection,
             'modelsMember' => (empty($modelsMember)) ? [new ActivitySectionMember] : $modelsMember,
         ]);
     }

    /**
     * Updates an existing Activity model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
     public function actionUpdate($id) {

         // retrieve existing Deposit data
         $model = Activity::find()->where(['id'=>$id])->one();

         $ketua = ActivityMainMember::find()
                    ->where(['activity_id'=>$id])
                    ->andWhere(['name_committee'=>"Ketua"])->one();
         $wakil = ActivityMainMember::find()
                    ->where(['activity_id'=>$id])
                    ->andWhere(['name_committee'=>"Wakil"])
                    ->one();
         $sekretaris = ActivityMainMember::find()
                    ->where(['activity_id'=>$id])
                    ->andWhere(['name_committee'=>"Sekretaris"])
                    ->one();
         $bendahara = ActivityMainMember::find()
                    ->where(['activity_id'=>$id])
                    ->andWhere(['name_committee'=>"Bendahara"])
                    ->one();

          $budget = ActivityBudgetSecretariat::find()->where(['activity_id'=>$model])->one();
          $awal = ActivityBudgetSecretariat::find()->where(['secretariat_budget_id'=>$budget])->one();
          $baru = SecretariatBudget::find()->where(['id'=>$awal])->one();
          $range = $model->date_start.' to '.$model->date_end;
          $range_start = $model->date_start;
          $range_end = $model->date_end;
         // retrieve existing ActivitySection data
         $oldActivitySectionIds = ActivitySection::find()->select('id')
             ->where(['activity_id' => $id])->asArray()->all();
         $oldActivitySectionIds = ArrayHelper::getColumn($oldActivitySectionIds,'id');
         $modelsSection = ActivitySection::findAll(['id' => $oldActivitySectionIds]);
         $modelsSection = (empty($modelsSection)) ? [new ActivitySection] : $modelsSection;

         // retrieve existing Loads data
         $oldLoadIds = [];
         foreach ($modelsSection as $i => $modelSection) {
             $oldLoads = ActivitySectionMember::findAll(['section_activity_id' => $modelSection->id]);
             $modelsMember[$i] = $oldLoads;
             $oldLoadIds = array_merge($oldLoadIds,ArrayHelper::getColumn($oldLoads,'id'));
             $modelsMember[$i] = (empty($modelsMember[$i])) ? [new ActivitySectionMember] : $modelsMember[$i];
         }

         // handle POST
         if ($model->load(Yii::$app->request->post())) {


             $post = Yii::$app->request->post();
             $model->role = Yii::$app->user->identity->role;
             $model->finance_status = 0;
             $model->department_status = 0;
             $model->chief_status = 0;
             $model->done = 0;
             $model->date_start = $post['from_date'];
             $model->date_end = $post['to_date'];

             // get ActivitySection data from POST
             $modelsSection = Model::createMultiple(ActivitySection::classname(), $modelsSection);
             Model::loadMultiple($modelsSection, Yii::$app->request->post());
             $newActivitySectionIds = ArrayHelper::getColumn($modelsSection,'id');

             // get ActivitySectionMember data from POST
             $newLoadIds = [];
             $loadsData =  Yii::$app->request->post();
             for ($i=0; $i<count($modelsSection); $i++) {
                 $loadsData['ActivitySectionMember'] =  Yii::$app->request->post()['ActivitySectionMember'][$i];
                 $modelsMember[$i] = Model::createMultiple(ActivitySectionMember::classname(),$modelsMember[$i] ,$loadsData);
                 Model::loadMultiple($modelsMember[$i], $loadsData);
                 $newLoadIds = array_merge($newLoadIds,ArrayHelper::getColumn($loadsData['ActivitySectionMember'],'id'));
             }

             // delete removed data
             $delLoadIds = array_diff($oldLoadIds,$newLoadIds);
             if (! empty($delLoadIds)) ActivitySectionMember::deleteAll(['id' => $delLoadIds]);
             $delActivitySectionIds = array_diff($oldActivitySectionIds,$newActivitySectionIds);
             if (! empty($delActivitySectionIds)) ActivitySection::deleteAll(['id' => $delActivitySectionIds]);

             // validate all models
             $valid = $model->validate();

             // save deposit data
             if ($valid) {
                 if ($this->saveDeposit($model,$modelsSection,$modelsMember)) {

                   if($post){
                     if($post['ketua']){
                       $modelsMain = ActivityMainMember::find()->where(['activity_id'=>$id])->andWhere(['name_committee'=>'Ketua'])->one();
                       $modelsMain->name_member = $post['ketua'];
                       $modelsMain->save();
                     }
                     if($post['wakil']){
                       $modelsMain = ActivityMainMember::find()->where(['activity_id'=>$id])->andWhere(['name_committee'=>'Wakil'])->one();
                       $modelsMain->name_member = $post['wakil'];
                       $modelsMain->save();
                     }
                     if($post['sekretaris']){
                       $modelsMain = ActivityMainMember::find()->where(['activity_id'=>$id])->andWhere(['name_committee'=>'Sekretaris'])->one();
                       $modelsMain->name_member = $post['sekretaris'];
                       $modelsMain->save();
                     }
                     if($post['bendahara']){
                       $modelsMain = ActivityMainMember::find()->where(['activity_id'=>$id])->andWhere(['name_committee'=>'Bendahara'])->one();
                       $modelsMain->name_member = $post['bendahara'];
                       $modelsMain->save();
                     }
                   }

                     return $this->redirect('index');
                 }
             }
         }

         // show VIEW
         return $this->render('_form-update', [
             'model' => $model,
             'ketua' => $ketua,
             'wakil' => $wakil,
             'bendahara' => $bendahara,
             'sekretaris' => $sekretaris,
             'modelsSection'  => $modelsSection,
             'modelsMember' => $modelsMember,
             'range' => $range,
             'range_start' => $range_start,
             'range_end' => $range_end,
         ]);
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
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
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

    protected function saveDeposit($model,$modelsSection,$modelsMember) {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            if ($go = $model->save(false)) {

                // loop through each payment
                foreach ($modelsSection as $i => $modelSection) {
                    // save the payment record
                    $modelSection->activity_id = $model->id;
                    if ($go = $modelSection->save(false)) {
                        // loop through each load
                        foreach ($modelsMember[$i] as $ix => $modelMember) {
                            // save the load record
                            $modelMember->activity_id = $model->id;
                            $modelMember->section_activity_id = $modelSection->id;
                            if (! ($go = $modelMember->save(false))) {
                                $transaction->rollBack();
                                break;
                            }

                        }
                    }
                }
            }
            if ($go) {
                $transaction->commit();
            }
        } catch (Exception $e) {
            $transaction->rollBack();
        }
        return $go;
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
                            Rp.".$data->secretariat_budget_value."
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
                            Rp.".$data->section_budget_value."
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
